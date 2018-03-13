# AWS Setup Guide 
#### (CODEDEPLOY & GitHub Auto-Deploy Guide)
Inspired by: The work of Glenn Steigerwald
Author: Harrison Bryant 


### Set up AWS IAM Permissions for EC2 & CodeDeploy
*****************************************************

-- Log into AWS console using admin access credentials: https://console.aws.amazon.com/console/home
-- Go to IAM Service: https://console.aws.amazon.com/iam/home

-- Create 2 ROLES:
	1.
		Name: CodeDeploy-EC2
		Role Type: AWS Service Role / Amazon EC2
		Policy: Attach Managed Policy 'AmazonEC2RoleforAWSCodeDeploy' - Gives the Ec2 server instance permission to download the codedeploy agent from s3. 
	
	2.
		Name: CodeDeploy
		Role Type: AWS Service Role / Amazon EC2
		Policy: Attach Mananged Policy 'CodeDeployPermissions' - Gives the CodeDeploy service permissions to do whatever it wants.
		**NOTE: You must also edit the 'Trust Relationships' for this role to associate it with codedeploy. After creating the role, go to its summary page and click 'Edit Trust Relationship' button from 'Trust Relationships' Tab.**
		
		Paste this into the editor, updating the Service url if necessary based on your location.

		{
		  "Version": "2012-10-17",
		  "Statement": [
		    {
		      "Sid": "",
		      "Effect": "Allow",
		      "Principal": {
		        "Service": "codedeploy.us-east-1.amazonaws.com"
		      },
		      "Action": "sts:AssumeRole"
		    }
		  ]
		}

		
-- Create 1 USER named 'github':
	Navigate to github user summary page
	Click 'Security Credentials' tab, 'Create Access Key'. SAVE BOTH THE ACCESS KEY ID AND SECRET ACCESS KEY, they are required later to configure the webserver.(Access key ID looks like AKIAI5GLM3KT2UCDAPSZ)
	Go to 'Permissions' tab, and Attach Managed Policy 'CodeDeployPermissions'
	Also Create Inline User Policy named 'DeploymentAPIAccess'.


	{
	    "Version": "2012-10-17",
	    "Statement": [
	        {
	            "Effect": "Allow",
	            "Action": "codedeploy:GetDeploymentConfig",
	            "Resource": "arn:aws:codedeploy:us-east-1:{{ACCOUNTID}}:deploymentconfig:*"
	        },
	        {
	            "Effect": "Allow",
	            "Action": "codedeploy:RegisterApplicationRevision",
	            "Resource": "arn:aws:codedeploy:us-east-1:{{ACCOUNTID}}:application:{{CODEDEPLOYAPPLICATION}}"
	        },
	        {
	            "Effect": "Allow",
	            "Action": "codedeploy:GetApplicationRevision",
	            "Resource": "arn:aws:codedeploy:us-east-1:{{ACCOUNTID}}:application:{{CODEDEPLOYAPPLICATION}}"
	        },
	        {
	            "Effect": "Allow",
	            "Action": "codedeploy:CreateDeployment",
	            "Resource": "arn:aws:codedeploy:us-east-1:{{ACCOUNTID}}:deploymentgroup:{{CODEDEPLOYAPPLICATION}}/{{DEPLOYMENTGROUP}}"
	        }
	    ]
	}

	
	**Note: Replace {{ACCOUNTID}} WITH AWS Account ID (click on name in top toolbar -> My account)**
	**Note: Replace  {{CODEDEPLOYAPPLICATION}} with CodeDeploy application name. (you may not have set this up yet... just put in a placeholder name for now)**
	**Note: Replace  {{DEPLOYMENTGROUP}} with Deployment group name for that application. (you may not have set this up yet... just put in a placeholder name for now)**




### Configure Github Repository to push to CodeDeploy
*****************************************************
			
-- Log into github on an admin account for repo. 

	1. Set Up CodeDeploy webhook - allows GitHub to push to CodeDeploy Application/Deployment Group.
	
 		- Go to repo you want to deploy and click the settings tab
 		- Click webhooks and services - use 'Add service' dropdown and add 'AWS CodeDeploy'
	  	Application name = {{CODEDEPLOYAPPLICATION}}
	  	Deployment Group = {{DEPLOYMENTGROUP}}
	  	Aws access key = github user access key I told you to write down above. Found under AWS -> IAM -> Users -> github -> Security Credentials Tab
			Aws region = us-east-1
	
	2. Set Up GitHub Auto-Deployment webhook - makes Github automatically push to CodeDeploy Application/Deployment Group when committed to. 
	
		- Go to repo you want to deploy and click the settings tab
 		- Click webhooks and services - use 'Add service' dropdown and add 'GitHub Auto-Deployment'

			GitHub token: Must create API token using your own personal github account and paste it here.
				- Click on your github user icon at the top and go to 'SEttings'
				- 'personal access tokens'
				- Generate New Token -> repo_deployment ONLY CHECKED OPTION
				- Write it down or save it, then input it into the field for the webhook.
			Environments: {{DEPLOYMENTGROUP}}





### Create and Configure your Ec2 Instance (Webserver
*****************************************************

-- Log into AWS console using admin access credentials: https://console.aws.amazon.com/console/home
-- Go to Ec2 Service: https://console.aws.amazon.com/ec2/v2/home
	
	1. Create your Ec2 Instance (Virtual Weberserver... in the cloud)

		- Go to 'Instances' and click 'Launch Instance' button. You can configure your server pretty much however you want with the following constraints:
			1. Must be Amazon Linux AMI
			2. Must assign IAM role 'CodeDeploy-EC2' you created in step 1.
			3. Must assign keypair for the instance which we'll use as a password to SSH in.
		
		
	2. Once EC2 server is online, SSH into your server: 
		**NOTE: you must navigate to your pem's directory and change its permissions to 400 before SSH. If you just created one, it's probably in your downloads folder... but you should move it to your website's git repository.**

		cd ~/Downloads/
		sudo chmod 400 ec2Pem.pem
		ssh -i ec2Pem.pem ec2-user@{{EC2 Server's IP Address or Public DNS Name}}		
		**NOTE: You can obtain your {{EC2 Server's IP Address or Public DNS Name}} from the Ec2 Panel in AWS where you first hit 'Launch Instance... it's at the bottom if you click on your instance.**


	3. Once SSH'd into your server, execute the following commands:
		
			sudo su
			yum -y update
			cd /home/ec2-user
			
			aws configure
			**Keys from github IAM user (told you to write them down before when creating this user)**
			Access Key ID: {{github IAM User Access Key ID}} 
			Secret Access Key: {{github IAM User Secret Access Key}}
			us-east-1
			json
			
			aws s3 cp s3://aws-codedeploy-us-east-1/latest/install . --region us-east-1
			chmod +x ./install
			./install auto
			
			service codedeploy-agent status
			**NOTE: This command just lets you know that everything went correctly**
		



### Create and Configure your CodeDeploy Application
****************************************************

-- Log into AWS console using admin access credentials: https://console.aws.amazon.com/console/home
-- Go to CodeDeploy Service: https://console.aws.amazon.com/codedeploy/home

	1. Create New Application
		- Name: {{CODEDEPLOYAPPLICATION}} This is the value you've been using this whole time. Feel free to go back and change all the values to this value if you want. (example....BrainDoDeploy)
		- Deployment Group Name: {{DEPLOYMENTGROUP}} This is the value you've been using this whole time. Feel free to go back and change all the values to this value if you want. (example...Staging)
		- Assign to it your Ec2 Instance which should have 'CodeDeploy-EC2' IAM role attached.
		- Deploy One At A Time
		- Assign Service Role ARN : CodeDeploy (Role we also created in step 1). 
	**Note: If you get 'could not assume role' error, make sure you added the 'Trust Relationship' configuration for the CodeDeploy Role.**

	2. Run your First Deployment
		- From the Application Summary, Click on your {{DEPLOYMENTGROUP}} tab and hid 'Deploy New Revision'.
		- Assign Application & Deployment group
		- Chose 'GitHub' Revision type and connect to your github account.
		- Grab the Repository Name (example.... braindo/braindosite2015)
		- Grab the most recent commit ID (from GitHub or SourceTree)
		- Deploy One At A Time
		- Deploy Now
		

Fin
