var express = require('express');
var path = require('path');
var bodyParser = require('body-parser');
var puppeteer = require('puppeteer');
var util = require('util');
var fs = require('fs');
var app = express();

function waitForFrame(page) {
  let fulfill;
  const promise = new Promise(x => fulfill = x);
  checkFrame();
  return promise;

  function checkFrame() {
    const frame = page.frames().find(f => f.url().includes('HomeDirect'));
    if (frame)
      fulfill(frame);
    else
      page.once('frameattached', checkFrame);
  }
}

app.use(bodyParser.json());

app.use(express.static(path.join(__dirname, 'public')));

app.use(bodyParser.urlencoded({ extended: false }));

// CORS Headers
app.use(function (req, res, next) {
  res.setHeader('Access-Control-Allow-Origin', '*');
  res.setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, PATCH, DELETE');
  res.setHeader('Access-Control-Allow-Headers', 'X-Requested-With,content-type');
  res.setHeader('Access-Control-Allow-Credentials', true);
  next();
});

// Timestamp
app.use(function (req, res, next) {
  console.log("----------------------Request Recieved!--------------------");
  next();
})

// Create Puppeteer Browser or Bind to Existing Browser
app.use("/chrome/g2gquote/:step", async(req,res,next) => {
  var browserWSEndpoint = req.body.browserWSEndpoint;
  var browser, page;
  
  // Browser Connect / Creation
  if(browserWSEndpoint){
    try{
      browser = await puppeteer.connect({
        browserWSEndpoint: browserWSEndpoint
      });
      
      var pages = await browser.pages();
      
      if(pages.length){        
        for(var i = 0; i < pages.length; i++){
          var curPage = pages[i];
          var pageHref = await curPage.evaluate(() => document.location.href);
          if(/good2go\.com/i.test(pageHref)){
            page = curPage;
            break;
          }else{
            curPage.close();
          } 
        }
      }else{
        page = await browser.newPage();
      }
      
      var pageHref = await page.evaluate(() => document.location.href);
      console.log("Existing Browser Connected: " + browserWSEndpoint);
      console.log("Page Connected: " + pageHref);

    }catch(e){
      //Error Creating Browser
      browser = await puppeteer.launch();
      page = await browser.newPage();
      console.log("Bad Endpoint Passed Through. Creating New Browser: " + browser.wsEndpoint());
    }
  }else{
    browser = await puppeteer.launch({headless: false});
    page = await browser.newPage();
    console.log("Endpoint Not Passed Through. Creating New Browser: " + browser.wsEndpoint());
  }
  
  //Page Config
  await page.setViewport({width: 1440, height: 900});
  await page.setRequestInterception(true);
  const block_ressources = ['image', 'stylesheet', 'media', 'font', 'texttrack', 'object', 'beacon', 'csp_report', 'imageset'];
  page.on('request', request => {
  	if (
  		block_ressources.indexOf(request.resourceType) > 0
  		// Be careful with above
  		|| request.url().includes('.jpg')
  		|| request.url().includes('.jpeg')
  		|| request.url().includes('.png')
  		|| request.url().includes('.gif')
  		|| request.url().includes('.css')
  	)
  		request.abort();
  	else
  		request.continue();
  });
  
  
  // Mkdir for screenshots
  var browserId = /\/browser\/.+$/.test(browser.wsEndpoint()) ? browser.wsEndpoint().match(/\/browser\/(.+)$/)[1]: "noId";
  var ssdir = "./public/puppeteer_screenshots/" + browserId;
  fs.existsSync(ssdir) || fs.mkdirSync(ssdir);
  
  res.locals.browser = browser;
  res.locals.browserWSEndpoint = browser.wsEndpoint();
  res.locals.page = page;
  res.locals.ssDir = ssdir;
  next();
});

// Step 1
app.post("/chrome/g2gquote/1", async (req, res, next) => {
  console.log("Completing step 1: Initial Zip Code");

  var browser = res.locals.browser;
  var browserWSEndpoint = res.locals.browserWSEndpoint;
  var page = res.locals.page;
  
  var zipcode = req.body.zipcode;
  var resMessage = "";
  var resErrorArray = [];
  var resQuoteStep = 1;
  
  //Fill In Initial Zip and click go
  await page.goto("https://awsstaging.good2go.com");
  await page.click("#first-zip");
  await page.keyboard.type(zipcode);
  await page.$eval('.submit-button', el => el.click());

  // Wait for success or fail
  await page.waitForSelector(".first-zip.error, #g2g-form[style*='height']", {
    visible: true
  });
  
  // Success
  var isSuccess = await page.evaluate(() => {
    return document.querySelector("#g2g-form[style*='height']");
  });
      
  // Fail - zipError
  var zipErrorText = await page.evaluate(() => {
    var zipErrorElem = document.querySelector("label.error[for='first-zip']");
    return zipErrorElem && zipErrorElem.innerText;
  });
  
  if(isSuccess){
    resMessage = "success";
    resQuoteStep = 2;
    await browser.disconnect();     
    await res.json({
      browserWSEndpoint: browserWSEndpoint,
      message: resMessage,
      quoteStep: resQuoteStep,
      errorObj: resErrorArray
    });
  }else if(zipErrorText){
    await page.screenshot({path: res.locals.ssDir + "/zipError.jpg", fullPage: true});
    console.log("zipcode error, check zipError.jpg for details.");

    resErrorArray.push({
      errorFieldId: "zipcode",
      errorMessage: zipErrorText
    });
    resMessage = "error";
    resQuoteStep = 1;
    await res.json({
      browserWSEndpoint: browserWSEndpoint,
      message: resMessage,
      quoteStep: resQuoteStep,
      errorObj: resErrorArray
    });
  }
});

// Step 2
app.post("/chrome/g2gquote/2", async (req, res, next) => {
  console.log("Completing step 2: Personal Information");
  var browser = res.locals.browser;
  var page = res.locals.page;
  var browserWSEndpoint = res.locals.browserWSEndpoint;
  
  var resQuoteStep = 2;
  var resMessage = "";
  var resErrorArray = [];
  
  var fname = req.body.fname;
  var middle = req.body.middle;
  var lname = req.body.lname;
  var address1 = req.body.address1;
  var address2 = req.body.address2;
  var dob = req.body.dob;
     
  const frame = await waitForFrame(page); 
  
  // Fill in FName
  var input = await frame.$("#txtFName");
  await input.focus();
  await page.keyboard.type(fname);  

  // Fill in middle
  var input = await frame.$("#txtMid");
  await input.focus();
  await page.keyboard.type(middle);

  // Fill in last
  var input = await frame.$("#txtLName");
  await input.focus();
  await page.keyboard.type(lname); 
          
  // Fill in address1
  var input = await frame.$("#txtAdd1");
  await input.focus();
  await page.keyboard.type(address1); 
  
  // Fill in address2
  var input = await frame.$("#txtApt");
  await input.focus();
  await page.keyboard.type(address2); 

  // Fill in dob
  var input = await frame.$("#txtDOB");
  await input.focus();
  await page.keyboard.type(dob); 
  
  // Click Submit
  await page.$eval('.submit-button', el => el.click());
  await page.waitForNavigation();
  
  // Success!
  resMessage = "success";
  resQuoteStep = 3;        
  
  await browser.disconnect();     

  // Respond
  res.json({
    browserWSEndpoint: browserWSEndpoint,
    message: resMessage,
    quoteStep: resQuoteStep,
    errorObj: resErrorArray
  });

});

// Step 3
app.post("/chrome/g2gquote/3", async (req, res, next) => {
  console.log("Completing step 3: Vehicles and Drivers");
  var browser = res.locals.browser;
  var page = res.locals.page;
  var browserWSEndpoint = res.locals.browserWSEndpoint;
  
  var resQuoteStep = 3;
  var resMessage = "";
  var resErrorArray = [];

  var vin = req.body.vin;
  var maritalStatus = req.body.maritalStatus;
  var gender = req.body.gender;
  
  // Click VIN
  await page.click("#rbVinCar1_1");
  await page.waitForSelector("#txtFVin1");
  
  // Type in VIN
  await page.evaluate(() => {
    document.querySelector('#txtFVin1').value = "";
  });
  await page.click("#txtFVin1");
  await page.keyboard.type(vin);

  // Chose Marital Status
  await page.waitForSelector("#ddlMarStat1");
  await page.select("#ddlMarStat1", maritalStatus);
  
  // Gender
  await page.waitForSelector("[name='rGender1'][value='" + gender + "']");
  await page.click("[name='rGender1'][value='" + gender + "']");

  // Click Next
  await page.waitForSelector("#ibNextPage");
  await page.click("#ibNextPage");

  // Wait for success or fail
  await page.waitForSelector("#REVVeh1, #ddlVLF1", {
    visible: true
  });
  
  var success = await page.evaluate(() => document.querySelector("#ddlVLF1"));
  var vinErrorText = await page.evaluate(() => {
    var vinErrorElem = document.querySelector("#REVVeh1");
    return vinErrorElem && vinErrorElem.innerText;
  });
  
  if(success){
    resMessage = "success";
    resQuoteStep = 4;  
  }else if(vinErrorText){
  await page.screenshot({path: res.locals.ssDir + "/vinerror.jpg", fullPage: true});

    resMessage = "error";
    resQuoteStep = 3;
    resErrorArray.push({
      errorFieldId: "vin",
      errorMessage: vinErrorText
    });
  }

  await browser.disconnect();     

  // Respond
  res.json({
    browserWSEndpoint: browserWSEndpoint,
    message: resMessage,
    quoteStep: resQuoteStep,
    errorObj: resErrorArray
  }); 
});

// Step 4
app.post("/chrome/g2gquote/4", async (req, res, next) => {
  console.log("Completing step 4: Vehicles, Drivers, and Quote Info");
  var browser = res.locals.browser;
  var page = res.locals.page;
  var browserWSEndpoint = res.locals.browserWSEndpoint;
  
  var resQuoteStep = 4;
  var resMessage = "";
  var resErrorArray = [];

  var vehType = req.body.vehType;
  var purchaseDays = req.body.purchaseDays;
  var licenseNumber = req.body.licenseNumber;
  var licenseYear = req.body.licenseYear;
  var licenseValid = req.body.licenseValid;
  var pipLimit = req.body.pipLimit;
  var pipDeductable = req.body.pipDeductable;
  
  
  try{
    // Select Vehicle Type
    await page.select("#ddlVLF1", vehType);
    
    // Select Purchase 90 Days
    await page.waitForSelector("#ddlVRC1 option[selected]");        
    await page.select("#ddlVRC1", purchaseDays);
    
    // License Number
    await page.click("#txtLicNum1");
    await page.keyboard.type(licenseNumber);
    
    // License Year
    await page.select("#ddlDLic1", licenseYear);
    
    // License Valid
    await page.select("#ddLicStat1", licenseValid);
    
    // PIP limit
    await page.select("[id='3']", pipLimit);
    
    // PIP Deductable
    await page.select("[id='4']", pipDeductable);
    
    // Click "Update Quote"
    await page.$eval('#ibNextPage', el => el.click());
     
    // Wait Button Hidden
    await page.waitForSelector("#ibNextPage", {hidden:true});
    
    // Loading Spinner ...
    
    // Wait Button To Reappear
    await page.waitForSelector("#ibNextPage", {visible:true});   
    
    // Click Next
    await page.$eval('#ibNextPage', el => el.click());
  
    await page.waitForSelector("#CN", {
      visible:true
    });   
    
    // Success!
    resMessage = "success";
    resQuoteStep = 5;        
    
    await browser.disconnect();     
  
    // Respond
    res.json({
      browserWSEndpoint: browserWSEndpoint,
      message: resMessage,
      quoteStep: resQuoteStep,
      errorObj: resErrorArray
    }); 
  }catch(e){
    await page.screenshot({path: res.locals.ssDir + "/timeoutError.jpg", fullPage: true});
    
    await browser.close();     

    resMessage = "error";
    resQuoteStep = 1;
    resErrorArray.push({
      errorFieldId: "meta",
      errorMessage: "timeout" 
    });
    res.json({
      browserWSEndpoint: browserWSEndpoint,
      message: resMessage,
      quoteStep: resQuoteStep,
      errorObj: resErrorArray
    }); 
  }

        
});

// Step 5
app.post("/chrome/g2gquote/5", async (req, res, next) => {
  console.log("Completing step 5: Payment Information");
  var browser = res.locals.browser;
  var page = res.locals.page;
  var browserWSEndpoint = res.locals.browserWSEndpoint;
  
  var resQuoteStep = 5;
  var resMessage = "";
  var resErrorArray = [];

  var ccNumber = req.body.ccNumber;
  var ccExpMonth = req.body.ccExpMonth;
  var ccExpYear = req.body.ccExpYear;
  var email = req.body.email;
  var phone = req.body.phone;
  var cellPhone = req.body.cellPhone || req.body.phone;
  var policyNumber;
  
  // G2GSUM.aspx
  // -----------
  
  // CC Number
  await page.waitForSelector("#CN");
  await page.evaluate((ccNumber) => {
    document.querySelector('#CN').value = ccNumber;
  },ccNumber);
    
  // CC Month
  await page.select("#DDLMm", ccExpMonth);
  
  // CC Year
  await page.select("#DDLYy", ccExpYear);
    
  // Email
  await page.waitForSelector("#txtEmail");
  await page.evaluate((email) => {
    document.querySelector('#txtEmail').value = email;
  },email);

  // Conf Email
  await page.waitForSelector("#txtEmailRe");
  await page.evaluate((email) => {
    document.querySelector('#txtEmailRe').value = email;
  },email);
    
  // Phone
  await page.waitForSelector("#txtPhone");
  await page.evaluate((phone) => {
    document.querySelector('#txtPhone').value = phone;
  },phone);
  
  // Cell Phone
  await page.waitForSelector("#txtCellPhone");
  await page.evaluate((cellPhone) => {
    document.querySelector('#txtCellPhone').value = cellPhone;
  },cellPhone);
  
  // Click Submit
  await page.$eval('#ibBuyNow', el => el.click());

  
  // Wait for success or fail
  await page.waitForSelector("#ibBuyNow[src='assets/images/buy-now.gif'], #nameTag", {
    visible: true
  });
  
  // Success 
  var success = await page.evaluate(() => document.querySelector("#nameTag"));
  
  // Error
  var errorText = await page.evaluate(() => {
    var elem = document.querySelector("#lblBuyNow"); //TODO
    return elem && elem.innerText;
  });  
  
  
  if(success){
    await page.screenshot({path: res.locals.ssDir + "/paymentComplete.jpg", fullPage: true});
    policyNumber = await page.evaluate(() => /PolicyID=\d+/.test(location.search) && location.search.match(/PolicyID=(\d+)/)[1]);
    resMessage = "success";
    resQuoteStep = 5;
    await browser.close(); 
  }else if(/change to your premium/i.test(errorText)){
    await page.screenshot({path: res.locals.ssDir + "/errorPremiumChange.jpg", fullPage: true});
    var downPayment = await page.evaluate(() => document.querySelector("#lblDPay").innerText);
    var monthlyPayment = await page.evaluate(() => document.querySelector("#lblMPay").innerText);
    var totalPayment = await page.evaluate(() => document.querySelector("#lblPTot").innerText);

    resMessage = "error";
    resQuoteStep = 5;
    resErrorArray.push({
      errorFieldId: "meta",
      errorMessage: "Premium changed. please resubmit",
      downPayment: downPayment,
      monthlyPayment: monthlyPayment,
      totalPayment: totalPayment
    });
    await browser.disconnect();     
  }else if(/credit card number is not valid/i.test(errorText)){   
    await page.screenshot({path: res.locals.ssDir + "/errorCCInvalid.jpg", fullPage: true});  
    resMessage = "error";
    resQuoteStep = 5;     
    resErrorArray.push({
      errorFieldId: "ccNumber",
      errorMessage: "Credit Card Transaction did not process, credit card is not valid."
    });
    await browser.disconnect();     
  }else{
    await page.screenshot({path: res.locals.ssDir + "/5error.jpg", fullPage: true});
    resMessage = "error";
    resQuoteStep = 5;
    resErrorArray.push({
      errorFieldId: "meta",
      errorMessage: "something else..."
    });
    await browser.disconnect();     
  }

  // Respond
  res.json({
    browserWSEndpoint: browserWSEndpoint,
    message: resMessage,
    quoteStep: resQuoteStep,
    errorObj: resErrorArray,
    policyNumber:policyNumber
  }); 
  
});

// error handler
app.use(function(err, req, res, next) {
  // set locals, only providing error in development
  res.locals.message = err.message;
  res.locals.error = req.app.get('env') === 'development' ? err : {};

  // render the error page
  res.status(err.status || 500);
  res.render('error');
});

module.exports = app;
