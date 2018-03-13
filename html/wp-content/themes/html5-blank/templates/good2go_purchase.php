<?php /* Template Name: Good2Go Purchase */ get_header(); ?>
  <style>
  html,body{
    background: #FFFFFF;
  }
  .main-container{
    padding: 40px 0 100px 0;
  }
  input.error{
    border: 1px solid red !important;
  }
  small.error{
    color: red;
  }
  [readonly]{
    opacity: .5;
    cursor: not-allowed;
  }
  .step-complete{
    display: none;
    margin:0 0 20px 0;
    font-size:20px;
    color: #33c733;
    font-size:16px;
  }
  h3,h5{
    text-align: center;
  }
  .success-box h1{
    text-align: center;
    font-size:40px;
    text-align: left;
  }
  
  .success-box h3{
    text-align: left;
    font-size: 16px;
    color: #33c733;
  }
  #errorModal p{
    color:red;
    font-weight: bold;
  }
  
  </style>
  <!-- Error Modal -->
  <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Processing Error</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>There was an error creating your policy with the information entered. Please call 855-444-4444 to obtain your Good2Go policy.</p>
        </div>
      </div>
    </div>
  </div>
  <!-- /Error Modal -->
  
  <!-- Processing Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Processing...</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Waiting -->
          <div class="status-box waiting-box">
            <div class="progress-box">
              <span id="step-1-complete" class="step-complete">Step 1 Complete! (www.good2go.com: Initial Zipcode)</span>
              <span id="step-2-complete" class="step-complete">Step 2 Complete! (direct.good2go.com: Personal Information)</span>
              <span id="step-3-complete" class="step-complete">Step 3 Complete! (quote.good2go.com/G2GVeh1.aspx)</span>
              <span id="step-4-complete" class="step-complete">Step 4 Complete! (quote.good2go.com/G2GBuy.aspx)</span>
              <span id="step-5-complete" class="step-complete">Step 5 Complete! (quote.good2go.com/G2GSum.aspx)</span>
            </div>
            <!-- Success -->
            <div class="status-box success-box" style="display:none;">
              <h1>Payment Complete!</h1>
              <h3>Please check your email and follow instructions to esign your new Good2Go policy (Policy Number: <span class="ret-pol-num"></span>)</h3>
            </div>
            <!-- /Success -->
          </div>
          <!-- /Waiting -->
        </div>
      </div>
    </div>
  </div>
  <!-- /Processing Modal -->

  <!-- Form Section -->
  <div class=" status-box form-box" style="display:block;">
    <h3>Some fields have been pre-populated based on information previously entered.</h3>
    <br>
    <h5>Please fill in the remaining details and click "Submit" to purchase your policy.</h5>
    <br>
    <br>
    <form action="#" method="post" id="g2gPolicy">
      <input type="hidden" name="browserWSEndpoint" id="browserWSEndpoint" value="" />
      <div class="row justify-content-center">
        <div class="col-8">
          
          <!-- ZIP code -->
          <div class="form-group row">
            <label for="zipcode" class="col-4 col-form-label">ZIP Code</label>
            <div class="col-8">
              <input type="tel" id="zipcode" name="zipcode" value="08401" class="form-control">
              <small class="error" style="display:none;">ZIP code must be 5 digits.</small>
            </div>
          </div>
          <!-- /ZIP code -->
  
          <!-- First Name -->
          <div class="form-group row">
            <label for="fname" class="col-4 col-form-label">First Name</label>
            <div class="col-8">
              <input type="text" id="fname" name="fname" value="John" class="form-control">
            </div>
          </div>
          <!-- /First Name-->
          
          <!-- Middle Initial -->
          <div class="form-group row">
            <label for="middle" class="col-4 col-form-label">Middle Initial</label>
            <div class="col-8">
              <input type="text" id="middle" name="middle" value="J" class="form-control">
            </div>
          </div>
          <!-- /Middle Initial-->
          
          <!-- Last Name -->
          <div class="form-group row">
            <label for="lname" class="col-4 col-form-label">Last Name</label>
            <div class="col-8">
              <input type="text" id="lname" name="lname" value="Smith" class="form-control">
            </div>
          </div>
          <!-- /Last Name-->
          
          <!-- Address 1 -->
          <div class="form-group row">
            <label for="address1" class="col-4 col-form-label">Main Address</label>
            <div class="col-8">
              <input type="text" id="address1" name="address1" value="100 N MAINE AVE" class="form-control">
            </div>
          </div>
          <!-- /Address 1-->
          
          <!-- Address 2 -->
          <div class="form-group row">
            <label for="address2" class="col-4 col-form-label">Apt/Suite/Floor</label>
            <div class="col-8">
              <input type="tel" id="address2" name="address2" value="1" class="form-control">
            </div>
          </div>
          <!-- /Address 2 --> 
          
          <!-- DOB -->
          <div class="form-group row">
            <label for="address2" class="col-4 col-form-label">Date of Birth (MM/DD/YYYY)</label>
            <div class="col-8">
              <input type="text" id="dob" name="dob" value="12/12/1956" class="form-control">
            </div>
          </div>
          <!-- /DOB --> 
  
          <!-- VIN -->
          <div class="form-group row">
            <label for="vin" class="col-4 col-form-label">VIN</label>
            <div class="col-8">
              <input type="text" id="vin" name="vin" value="1HGCT1B84EA012118" class="form-control">
              <small class="error" style="display:none;">Incorrect VIN number.</small>
            </div>
          </div>
          <!-- /VIN -->
  
          <!-- Martial Status -->
          <div class="form-group row">
            <label for="maritalStatus" class="col-4 col-form-label">Marital Status</label>
            <div class="col-8">
              <select id="maritalStatus" name="maritalStatus" class="form-control">
            		<option value="S">Single</option>
            		<option value="D">Divorced</option>
            		<option value="M">Married</option>
            		<option value="X">Separated</option>
            		<option value="K">Single w/ cust of kids</option>
            		<option value="W">Widowed</option>
            		<option value="U">Civil Union</option>
              </select>
            </div>
          </div>
          <!-- /Martial Status --> 
  
          <!-- Gender -->
          <div class="form-group row">
            <label for="gender" class="col-4 col-form-label">Gender</label>
            <div class="col-8">
              <select id="gender" name="gender" class="form-control">
            		<option value="M">Male</option>
            		<option value="F">Female</option>
              </select>
            </div>
          </div>
          <!-- /Gender --> 
  
          <!-- Vehicle Type -->
          <div class="form-group row">
            <label for="vehType" class="col-4 col-form-label">Vehicle Type</label>
            <div class="col-8">
              <select id="vehType" name="vehType" class="form-control">
          			<option value="O">Owned</option>
          			<option value="F">Financed (making payments)</option>
          			<option value="L">Leased</option>
              </select>
            </div>
          </div>
          <!-- /Vehicle Type --> 
  
          <!-- Purchase Days -->
          <div class="form-group row">
            <label for="purchaseDays" class="col-4 col-form-label">Was Vehicle Purchased in Last 90 Days?</label>
            <div class="col-8">
              <select id="purchaseDays" name="purchaseDays" class="form-control">
                <option value="Y">Yes</option>
                <option value="N">No</option>
              </select>
            </div>
          </div>
          <!-- /Purchase Days --> 
  
          <!-- License Number -->
          <div class="form-group row">
            <label for="licenseNumber" class="col-4 col-form-label">License Number</label>
            <div class="col-8">
              <input type="text" id="licenseNumber" name="licenseNumber" value="D00014070008801" class="form-control">
            </div>
          </div>
          <!-- /License Number -->
            
          <!-- Purchase Days -->
          <div class="form-group row">
            <label for="licenseYear" class="col-4 col-form-label">Licensed Year</label>
            <div class="col-8">
              <select id="licenseYear" name="licenseYear" class="form-control">
          			<option value="1111">Prior 2015</option>
                <option value="2018">2018</option>
          			<option value="2017">2017</option>
          			<option value="2016">2016</option>
          			<option value="2015">2015</option>
              </select>
            </div>
          </div>
          <!-- /Purchase Days --> 
        
          <!-- License Valid -->
          <div class="form-group row">
            <label for="licenseValid" class="col-4 col-form-label">Licensed Status</label>
            <div class="col-8">
              <select id="licenseValid" name="licenseValid" class="form-control">
                <option value="VALID">Valid</option>
                <option value="SUSPENDED">Suspended</option>
                <option value="REVOKED">Revoked</option>
              </select>
            </div>
          </div>
          <!-- /License Valid --> 
  
          <!-- PIP Limit -->
          <div class="form-group row">
            <label for="pipLimit" class="col-4 col-form-label">PIP Limit</label>
            <div class="col-8">
              <select id="pipLimit" name="pipLimit" class="form-control">
                <option value="15000">15,000 PIP Medical Expense</option>
            		<option value="50000">50,000 PIP Medical Expense</option>
            		<option value="75000">75,000 PIP Medical Expense</option>
            		<option value="150000">150,000 PIP Medical Expense</option>
            		<option value="250000">250,000 PIP Medical Expense</option>
              </select>
            </div>
          </div>
          <!-- /PIP Limit --> 

          <!-- PIP Deductable -->
          <div class="form-group row">
            <label for="pipDeductable" class="col-4 col-form-label">PIP Deductible</label>
            <div class="col-8">
              <select id="pipDeductable" name="pipDeductable" class="form-control">
                <option value="2500">2,500</option>
            		<option value="2000">2,000</option>
            		<option value="1000">1,000</option>
            		<option value="500">500</option>
            		<option value="250">250</option>
              </select>
            </div>
          </div>
          <!-- /PIP Deductable --> 

          <!-- CC Number -->
          <div class="form-group row">
            <label for="ccNumber" class="col-4 col-form-label">Credit Card Number</label>
            <div class="col-8">
              <input type="tel" id="ccNumber" name="ccNumber" value="111" class="form-control">
              <small class="error" style="display:none;">Credit card not accepted.</small>
            </div>
          </div>
          <!-- /CC Number -->

          <!-- CC Exp Month -->
          <div class="form-group row">
            <label for="ccExpMonth" class="col-4 col-form-label">Credit Card Month Expiry</label>
            <div class="col-8">
              <select id="ccExpMonth" name="ccExpMonth" class="form-control">
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
            </div>
          </div>
          <!-- /CC Exp Month --> 

          <!-- CC Exp Year -->
          <div class="form-group row">
            <label for="ccExpYear" class="col-4 col-form-label">Credit Card Year Expiry</label>
            <div class="col-8">
              <select id="ccExpYear" name="ccExpYear" class="form-control">
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
            </div>
          </div>
          <!-- /CC Exp Year --> 

          <!-- Email -->
          <div class="form-group row">
            <label for="email" class="col-4 col-form-label">Email</label>
            <div class="col-8">
              <input type="email" id="email" name="email" value="harrison@brain.do" class="form-control">
            </div>
          </div>
          <!-- /Email -->

          <!-- CONF Email -->
          <div class="form-group row">
            <label for="confEmail" class="col-4 col-form-label">Confirm Email</label>
            <div class="col-8">
              <input type="email" id="confEmail" name="confEmail" value="harrison@brain.do" class="form-control">
            </div>
          </div>
          <!-- /CONF Email -->

          <!-- Phone -->
          <div class="form-group row">
            <label for="phone" class="col-4 col-form-label">Phone Number</label>
            <div class="col-8">
              <input type="tel" id="phone" name="phone" value="555-555-5554" class="form-control">
            </div>
          </div>
          <!-- /Phone -->

          <!-- Cell -->
          <div class="form-group row">
            <label for="cellPhone" class="col-4 col-form-label">Cellphone Number</label>
            <div class="col-8">
              <input type="tel" id="cellPhone" name="cellPhone" value="555-555-5554" class="form-control">
            </div>
          </div>
          <!-- /Cell -->
          
          <!-- submit -->
          <div class="row justify-content-center">
            <div class="col-12">
              <p style="display:none;" class="form-error"></p>
              <button type="submit" value="Submit" class="btn btn-primary">Submit</button>
            </div>
          </div>
          <!-- /submit -->
          
        </div>
      </div>
    </form>
  </div>
  <!-- /Form Section -->
  
  <script>

    function showError($errorField, errorMessage){
      $errorField.removeAttr("readonly");
      $errorField.addClass("error");
      $errorField.next().show().text(errorMessage || "Invalid Input.");
      $('html, body').animate({ scrollTop: $errorField.offset().top - 50}, 0);
    }
    
    function hideError($errorField){
      $errorField.removeClass("error");
      $errorField.next().hide();
    }
    
    function handleErrors(data){
      var errorStep = data.quoteStep;
      var errorMessage = data.message;
      var errors = data.errorObj;
      
      // Loop through returned errors array
      for(var i = 0; i < errors.length; i++){
        var error = errors[i];
        var errorFieldId = error.errorFieldId;
        var errorMessage = error.errorMessage;
        
        if(errorFieldId == "meta" && errorMessage == "timeout"){
          $('#myModal').modal("hide");
          $("#errorModal").modal("show");
          $(".step-complete").hide();
          window.step = 1;
          return; 
        }
        
        if(errorFieldId == "meta" && errorMessage == "Premium changed. please resubmit"){
          var downPayment = error.downPayment;
          var monthlyPayment = error.monthlyPayment;
          var totalPayment = error.totalPayment;
          
          $('#myModal').modal("hide");
  
          $(".form-error").show().text("Your premium has been adjusted based on your credit report. Click submit to purchase your policy with the following rates. Down Payment: " + downPayment + " Monthly Payment: " + monthlyPayment + " Total Payment: " + totalPayment).css("color","red");
          
          $('html, body').animate({ scrollTop: $(".form-error").offset().top + 100}, 0);
          
          $(".btn").removeClass("btn-primary").addClass("btn-danger").text("Confirm");
          
          $(".btn.btn-danger").click(function(){
            $(".btn").addClass("btn-primary").removeClass("btn-danger").text("Submit");
            $(".form-error").hide();
          });
          return; 
        }

        var $errorField = $("#" + errorFieldId);
        showError($errorField, errorMessage);
      }
      
      
      $('#myModal').modal("hide");
      $('html, body').animate({ scrollTop: $(".error:first").offset().top - 100}, 0);
    }
    
    $(function(){   
      
      $("input").change(function(){
        $(this).removeClass("error");
        $(this).next(".error").hide();
      });   

      //Populate Inputs From URL
      var search = location.search.substring(1);
      var searchObj = JSON.parse('{"' + decodeURI(search).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');
      $.each(searchObj, function(key, value){
        var key = decodeURIComponent(key);
        var value = decodeURIComponent(value);
        $("#" + key).val(value);
      });
      
      function postSteps(){
        window.step = window.step || 1;
                
        $.post("http://" + location.hostname + ":3000/chrome/g2gquote/" + window.step, $("#g2gPolicy").serialize(), function(data){

          window.step = data.quoteStep
          var browserWSEndpoint = data.browserWSEndpoint;
          var message = data.message;
          var policyNumber = data.policyNumber;
          
          $("#browserWSEndpoint").val(browserWSEndpoint);
          
          if(message == "success" && !policyNumber){
            $("#step-" + (step-1) + "-complete").css("display","block");
            postSteps();
          }
          
          if(message == "success" && policyNumber){ 
            $(".ret-pol-num").text(policyNumber);
            $(".success-box").show();
            $("#exampleModalLabel").text("Success!")
          }
        
          if(message == "error"){
            handleErrors(data);
          }
          
        });
      }
      
      $("#g2gPolicy").submit(function(e){
        e.preventDefault();
        $("input").removeClass("error");
        $("span.error").hide();
        var valid = true;
        
        // Do front end validation
        
        //Zipcode
        var $zipcode = $("#zipcode");
        if($zipcode.val().length != 5){
          showError($zipcode, "Zipcode must be 5 digits.");
          valid = false;
        }else{
          hideError($zipcode);
        }
        
         
        if(valid){
          $('#myModal').modal("show");
          postSteps();
        }

      });
    });  
  </script>
<?php get_footer(); ?>