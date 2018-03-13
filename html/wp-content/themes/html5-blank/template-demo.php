<?php /* Template Name: Demo Page Template */ get_header(); ?>

<!--
  <div id="paypal">
    <div id="paypal-button-container"></div>
    <div id="paypal-paymentComplete" style="display:block;">
      <div class="header">
        <div class="left">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/PayPal.png" />
        </div>
        <div class="right">
          <span>Thank You For Your Payment</span>
        </div>  
      </div>
      
      <div class="details">
        <div class="right">
          <span>Transaction Amount:</span>
          <span id="paypal-amount">$613.00</span>
        </div>
        <div class="left">
          <span id="paypal-name">Gary Biddle</span>
          <span id="paypal-email">gary.biddle@gmail.com</span>
          <span id="paypal-city">Philadelphia</span>
          <span id="paypal-country">USA</span>
          <span id="paypal-zip">19123</span>
          <span id="paypal-state">PA</span>
        </div>
      </div>
      
      <div class="continue-esign">
        <div class="left">
          <span class="next-step">Next Step:</span><span>Sign your documents.</span>
        </div>
        <div class="right">
          <a href="//www.google.com">Continue to esign</a>
        </div>
      </div>
    </div>
    <script src="https://www.paypalobjects.com/api/checkout.js"></script>
    <script>
      // Fires on papal success
      // Accepts data object from paypal api success callback
      function paypalComplete(data){
        var payerInfo = data && data.payer && data.payer.payer_info;
        var shippingInfo = payerInfo && payerInfo.shipping_address;
        var transaction = data && data.transactions && data.transactions.length && data.transactions[0];
        var sale = transaction && transaction.related_resources && transaction.related_resources.length && transaction.related_resources[0] && transaction.related_resources[0].sale;
        if(!payerInfo || !transaction || !shippingInfo || !sale) return;
        
        console.log(JSON.stringify(data));
        
        //Payer Info
        var firstName = payerInfo.first_name;
        var lastName = payerInfo.last_name;
        var payerId = payerInfo.payer_id;
        var email = payerInfo.email;
        
        //Shipping Info
        var shippingCity = shippingInfo.city;
        var shippingAddress1 = shippingInfo.line1;
        var shippingZip = shippingInfo.postal_code;
        var shippingState = shippingInfo.state;
  
        //Transaction Info
        var saleTotal = sale.amount.total;
        var saleFee = sale.transaction_fee.value || "0.00";
        var saleId = data.id;
        
        $("#paypal-name").text(firstName + " " + lastName);
        $("#paypal-email").text(email); 
        $("#paypal-amount").text("$" + saleTotal);
          
        $("#paypal-state").text(shippingState);
        $("#paypal-zip").text(shippingZip);
        $("#paypal-city").text(shippingCity);
        $("#paypal-country").text("USA");
        
        $("#paypal-paymentComplete").show(); 
      }
      
      // Initializes paypal button and widget
      // Accepts paymentAmount
      function initializePaypal(paymentAmount){
        
        //Configure Dynamic Payment Variables
        var environment = /quote1?\.good2go\.com/.test(location.hostname) ? "production" : "sandbox";
        var paymentAmount = paymentAmount || "0.00";
        
        // Initialize Paypal Widget
        paypal.Button.render({
          env: environment, // sandbox | production
          
          style: {
            label: 'checkout', // checkout || credit
            size: 'medium', // tiny | small | medium
            shape: 'rect', // pill | rect
            color: 'gold' // gold | blue | silver
          },
          
          client: {
            sandbox: 'AeZGeaFp1yNXkX8G8S84fQH7jD6VYQLo3VTWw2IsvMSryWZC4ikJ_6jfxN8U-oPeT3zDp9hoqJ3egGSF',
            production: ''
          },
          
          payment: function() {
            return paypal.rest.payment.create(this.props.env, this.props.client, {
              transactions: [{
                amount: {
                  total: paymentAmount,
                  currency: 'USD'
                }
              }]
            });
          },
          
          onAuthorize: function(data, actions) {
            return actions.payment.execute().then(function(data) {
              if(data && data.state == "approved"){
                paypalComplete(data);
              }
            });
          },
          
          onError: function(data,actions){
            alert("Error processing payment");
          }
          
        }, '#paypal-button-container');
      }
      
      initializePaypal("613.00");
    </script>
  </div>
-->

<!--
<div class="container">
  <form action="<?php echo get_template_directory_uri(); ?>/php/contact.php" method="POST">

    <label for="fname">First Name</label>
    <input type="text" id="fname" name="firstname" placeholder="Your name..">

    <label for="lname">Last Name</label>
    <input type="text" id="lname" name="lastname" placeholder="Your last name..">

    <label for="country">Country</label>
    <select id="country" name="country">
      <option value="australia">Australia</option>
      <option value="canada">Canada</option>
      <option value="usa">USA</option>
    </select>

    <label for="subject">Subject</label>
    <textarea id="subject" name="subject" placeholder="Write something.." style="height:200px"></textarea>

    <input type="submit" value="Submit">

  </form>
</div>
-->

<style>
  [readonly]{
    opacity: .5;
    cursor: not-allowed;
  }
  </style>
<div class="container">
  <!-- Form Section -->
  <div class=" status-box form-box">
    <h1>Get A Quick Good2go Auto Insurance Policy!</h1>
    <p>Some of your information has been populated below. Please fill out the rest to recieve your policy.</p>
    <br>
    <form action="#" method="post" id="g2gPolicy">
      <input type="hidden" name="reqType" id="reqType" value="g2gQuote" />
      <div class="row">
        <div class="col-xs-12 col-md-6">
          <label for="zipcode">zipcode</label>
          <input type="text" id="zipcode" name="zipcode" value="08401" readonly>
          <br>
          
          <label for="fname">fname</label>
          <input type="text" id="fname" name="fname" value="Jim" readonly>
          <br>
          
          <label for="middle">middle</label>
          <input type="text" id="middle" name="middle" value="J" readonly>
          <br>
      
          <label for="lname">lname</label>
          <input type="text" id="lname" name="lname" value="Smith" readonly>
          <br>
      
          <label for="address1">address1</label>
          <input type="text" id="address1" name="address1" value="100 N MAINE AVE" readonly>
          <br>
      
          <label for="address2">address2</label>
          <input type="text" id="address2" name="address2" value="1" readonly>
          <br>
      
          <label for="dob">dob</label>
          <input type="text" id="dob" name="dob" value="12/12/1956" readonly>
          <br>
      
          <label for="vin">vin</label>
          <input type="text" id="vin" name="vin" value="1HGCT1B84EA012118" readonly>
          <br>
      
          <label for="maritalStatus">maritalStatus</label>
          <select id="maritalStatus" name="maritalStatus" readonly>
        		<option value="S">Single</option>
        		<option value="D">Divorced</option>
        		<option value="M">Married</option>
        		<option value="X">Separated</option>
        		<option value="K">Single w/ cust of kids</option>
        		<option value="W">Widowed</option>
        		<option value="U">Civil Union</option>
          </select>
          <br>
          
          <label for="gender">gender</label>
          <select id="gender" name="gender" readonly>
        		<option value="M">Male</option>
        		<option value="F">Female</option>
          </select>
          <br>
      
          <label for="vehType">vehType</label>
          <select id="vehType" name="vehType" readonly>
      			<option value="O">Owned</option>
      			<option value="F">Financed (making payments)</option>
      			<option value="L">Leased</option>
          </select>
          <br>
        </div>
        <div class="col-xs-12 col-md-6">
          <label for="purchaseDays">Vehicle Purchased in Last 90 Days?</label>
          <select id="purchaseDays" name="purchaseDays">
            <option value="Y">Yes</option>
            <option value="N">No</option>
          </select>
          <br>
      
          <label for="licenseNumber">licenseNumber</label>
          <input type="text" id="licenseNumber" name="licenseNumber" value="D00014070008801" >
          <br>
      
          <label for="licenseYear">licenseYear</label>
          <select id="licenseYear" name="licenseYear" readonly>
      			<option value="1111">Prior 2015</option>
            <option value="2018">2018</option>
      			<option value="2017">2017</option>
      			<option value="2016">2016</option>
      			<option value="2015">2015</option>
          </select>
          <br>
      
          <label for="licenseValid">licenseValid</label>
          <select id="licenseValid" name="licenseValid" readonly>
            <option value="VALID">Valid</option>
            <option value="SUSPENDED">Suspended</option>
            <option value="REVOKED">Revoked</option>
          </select>
          <br>
      
          <label for="pipLimit">pipLimit</label>
          <select id="pipLimit" name="pipLimit">
            <option value="15000">15,000 PIP Medical Expense</option>
        		<option value="50000">50,000 PIP Medical Expense</option>
        		<option value="75000">75,000 PIP Medical Expense</option>
        		<option value="150000">150,000 PIP Medical Expense</option>
        		<option value="250000">250,000 PIP Medical Expense</option>
          </select>
          <br>
      
          <label for="pipDeductable">pipDeductable</label>
          <select id="pipDeductable" name="pipDeductable">
            <option value="2500">2,500</option>
        		<option value="2000">2,000</option>
        		<option value="1000">1,000</option>
        		<option value="500">500</option>
        		<option value="250">250</option>
          </select>
          <br>
      
          <label for="ccNumber">ccNumber</label>
          <input type="text" id="ccNumber" name="ccNumber" value="4111111111111111">
          <br>
      
          <label for="ccExpMonth">ccExpMonth</label>
          <select id="ccExpMonth" name="ccExpMonth">
        		<option value="01">01</option>
        		<option value="02">02</option>
        		<option value="03">03</option>
        		<option value="04">04</option>
        		<option value="05">05</option>
        		<option value="06">06</option>
        		<option value="07">07</option>
        		<option value="08">08</option>
        		<option value="09">09</option>
        		<option value="10">10</option>
        		<option value="11">11</option>
        		<option value="12">12</option>
          </select>
          <br>
      
          <label for="ccExpYear">ccExpYear</label>
          <select id="ccExpYear" name="ccExpYear">
        		<option value="2019">2019</option>
        		<option value="2020">2020</option>
        		<option value="2021">2021</option>
        		<option value="2022">2022</option>
        		<option value="2023">2023</option>
        		<option value="2024">2024</option>
        		<option value="2025">2025</option>
        		<option value="2026">2026</option>
        		<option value="2027">2027</option>
        		<option value="2028">2028</option>
          </select>
          <br>
      
          <label for="email">email</label>
          <input type="text" id="email" name="email" value="harrison@brain.do" readonly>
          <br>
      
          <label for="confEmail">confEmail</label>
          <input type="text" id="confEmail" name="confEmail" value="harrison@brain.do" readonly>
          <br>
      
          <label for="phone">phone</label>
          <input type="text" id="phone" name="phone" value="555-555-5554" readonly>
          <br>
      
          <label for="cellPhone">cellPhone</label>
          <input type="text" id="cellPhone" name="cellPhone" value="555-555-5554" readonly>
          <br>
        </div>
        <div class="col-xs-12">
          <input type="submit" value="Submit">
        </div>
      </div>
    </form>
  </div>
  <!-- /Form Section -->
  
  <!-- Waiting -->
  <div class="status-box waiting-box" style="display:none;">
    <h1>Processing ....</h1>
  </div>
  <!-- /Waiting -->
  
  <!-- Success -->
  <div class="status-box success-box" style="display:none;">
    <h1>Payment Complete! Please check your email <span class="ret-email"></span> and follow instructions to esign your new Good2Go policy (Policy Number: <span class="ret-pol-num"></span>)</h1>
    <br>
    <br>
    <a href="#" id="start-new">Start Over</a>
  </div>
  <!-- /Success -->
</div>












<script>
$(function(){
  
  //Populate Inputs From URL
  var search = location.search.substring(1);
  var searchObj = JSON.parse('{"' + decodeURI(search).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');
  $.each(searchObj, function(key, value){
    $("#" + key).val(value).attr("readonly");
  });
  
  
  $("[readonly]").mousedown(function(e){
    e.preventDefault();
  })  
    
  $("#start-new").click(function(e){
    e.preventDefault();
    $(".status-box").hide();
    $(".form-box").show();
  });
  
  $("#g2gPolicy").submit(function(e){
    e.preventDefault();
    $(".status-box").hide();
    $(".waiting-box").show();
    
  
    $.post("http://www.hdev.online:3000", $(this).serialize(), function(data){
      
      //Success!
      if(data.status == "success"){
        var policyNumber = data.policyNumber;
        var email = data.emailAddress;
        var message = data.message;
        
        $(".status-box").hide();
        $(".ret-email").text(email);
        $(".ret-pol-num").text(policyNumber);
        $(".success-box").show();
  
  
      }else{
        console.log(data);
        $(".status-box").hide();
        $(".form-box h1").text(data.message);
        $(".form-box").show();
      }
  
    });
    
  });
});  
  

</script>
<?php get_footer(); ?>