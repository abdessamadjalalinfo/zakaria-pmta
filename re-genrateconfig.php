<?php //0046a

	die();
	include("inc/database.php");
	function generatePMTAServerConfig($ser_info , $ip_list , $smtp_list , $dkim_list  )
	{
	 
			$fdns_list = array();
		/*	$pmta_config_content = array();
			$q = "SELECT * FROM pmta_config ";
			$r = mysql_query($q) or die(mysql_error());
			while($row = mysql_fetch_array($r))
			{
				$pmta_config_content[$row['placeholder']] .= "\n" . $row['configuration'] . "\n"	;
			}
		*/
	
	
			$admin_access = explode("," ,$ser_info['esp_admin_iplist'] ); 
			$config = '';
			foreach($admin_access as $ip)
			{
				$ip = trim($ip);
				if($ip)
					$config .= "http-access ".$ip." admin\n";
			}	
	
			$config .= "postmaster admin@".$ser_info['esp_smtp_host']."\n";
	
			$config .= "<domain ".$ser_info['esp_smtp_host'].">"."\n";
			//$config .= "route [127.0.0.1]:25"."\n"; 
			$config .= "deliver-local-dsn   no"."\n";
			$config .= "</domain>"."\n";
		
			/*	
			$config .= "<domain ".$_POST['domain_names_'.$i].">"."\n";
			$config .= "route [".$_POST['primary_ip']."]:25"."\n";
			$config .= "deliver-local-dsn   yes"."\n";
			$config .= "</domain>\n"."\n";
			*/
			$config .= "# Settings for Backoff codes in SMTP reply
	
	<smtp-pattern-list SMTPRESPONS>
		reply /421 PR(ct1)/ mode=backoff
		reply /^550 SC-001/ mode=backoff
		reply /420 Resources unavailable temporarily/ mode=backoff
		reply /^Resources unavailable temporarily/ mode=backoff
		reply /^421/ mode=backoff
		reply /^450/ mode=backoff
		reply /^try later/ mode=backoff
		reply /^553/ mode=backoff
		reply /^421/ mode=backoff
		reply /^550/ mode=backoff
		reply /^553/ mode=backoff
		reply /^550 SC-001/ mode=backoff
		reply /^421 4.7.0/ mode=backoff
		reply /^busy/ mode=backoff
		reply /^WSAECONNREFUSED/ mode=backoff
		reply /^WSAECONNRESET/ mode=backoff
		reply /^Connection attempt failed/ mode=backoff
	</smtp-pattern-list>
	
	############################################################################
	# BEGIN: BACKOFF RULES
	############################################################################
	
	<smtp-pattern-list common-errors> 
		reply /generating high volumes of.* complaints from AOL/    mode=backoff 
		reply /Excessive unknown recipients - possible Open Relay/  mode=backoff 
		reply /^421 .* too many errors/                             mode=backoff 
		reply /blocked.*spamhaus/                                   mode=backoff 
		reply /451 Rejected/                                        mode=backoff 
	</smtp-pattern-list>
	
	<smtp-pattern-list blocking-errors>
		#
		# A QUEUE IN BACKOFF MODE WILL SEND MORE SLOWLY
		# To place a queue back into normal mode, a command similar
		# to one of the following will need to be run:
		# pmta set queue --mode=normal yahoo.com
		# or
		# pmta set queue --mode=normal yahoo.com/vmta1
		#
		# To use backoff mode, uncomment individual <domain> directives
		#
		#AOL Errors
		reply /421 .* SERVICE NOT AVAILABLE/ mode=backoff
		reply /generating high volumes of.* complaints from AOL/ mode=backoff
		reply /554 .*aol.com/ mode=backoff
		reply /421dynt1/ mode=backoff
		reply /HVU:B1/ mode=backoff
		reply /DNS:NR/ mode=backoff
		reply /RLY:NW/ mode=backoff
		reply /DYN:T1/ mode=backoff
		reply /RLY:BD/ mode=backoff
		reply /RLY:CH2/ mode=backoff
		#
		#Yahoo Errors
		reply /421 .* Please try again later/ mode=backoff
		reply /421 Message temporarily deferred/ mode=backoff
		reply /VS3-IP5 Excessive unknown recipients/ mode=backoff
		reply /VSS-IP Excessive unknown recipients/ mode=backoff
		#
		# The following 4 Yahoo errors may be very common
		# Using them may result in high use of backoff mode
		#
		reply /\[GL01\] Message from/ mode=backoff
		reply /\[TS01\] Messages from/ mode=backoff
		reply /\[TS02\] Messages from/ mode=backoff
		reply /\[TS03\] All messages from/ mode=backoff
		#
		#Hotmail Errors
		reply /exceeded the rate limit/ mode=backoff
		reply /exceeded the connection limit/ mode=backoff
		reply /Mail rejected by Windows Live Hotmail for policy reasons/ mode=backoff
		reply /mail.live.com\/mail\/troubleshooting.aspx/ mode=backoff
		#
		#Adelphia Errors
		reply /421 Message Rejected/ mode=backoff
		reply /Client host rejected/ mode=backoff
		reply /blocked using UCEProtect/ mode=backoff
		#
		#Road Runner Errors
		reply /Mail Refused/ mode=backoff
		reply /421 Exceeded allowable connection time/ mode=backoff
		reply /amIBlockedByRR/ mode=backoff
		reply /block-lookup/ mode=backoff
		reply /Too many concurrent connections from source IP/ mode=backoff
		#
		#General Errors
		reply /too many/ mode=backoff
		reply /Exceeded allowable connection time/ mode=backoff
		reply /Connection rate limit exceeded/ mode=backoff
		reply /refused your connection/ mode=backoff
		reply /try again later/ mode=backoff
		reply /try later/ mode=backoff
		reply /550 RBL/ mode=backoff
		reply /TDC internal RBL/ mode=backoff
		reply /connection refused/ mode=backoff
		reply /please see www.spamhaus.org/ mode=backoff
		reply /Message Rejected/ mode=backoff
		reply /Delivery report/ mode=backoff
		reply /refused by antispam/ mode=backoff
		reply /Service not available/ mode=backoff
		reply /currently blocked/ mode=backoff
		reply /locally blacklisted/ mode=backoff
		reply /not currently accepting mail from your ip/ mode=backoff
		reply /421.*closing connection/ mode=backoff
		reply /421.*Lost connection/ mode=backoff
		reply /476 connections from your host are denied/ mode=backoff
		reply /421 Connection cannot be established/ mode=backoff
		reply /421 temporary envelope failure/ mode=backoff
		reply /421 4.4.2 Timeout while waiting for command/ mode=backoff
		reply /450 Requested action aborted/ mode=backoff
		reply /550 Access denied/ mode=backoff
		reply /exceeded the rate limit/ mode=backoff
		reply /421rlynw/ mode=backoff
		reply /permanently deferred/ mode=backoff
		reply /\d+\.\d+\.\d+\.\d+ blocked/ mode=backoff
		reply /www\.spamcop\.net\/bl\.shtml/ mode=backoff
		reply /generating high volumes of.* complaints from AOL/    mode=backoff 
		reply /Excessive unknown recipients - possible Open Relay/  mode=backoff 
		reply /^421 .* too many errors/                             mode=backoff 
		reply /blocked.*spamhaus/                                   mode=backoff 
		reply /451 Rejected/                                        mode=backoff 
	</smtp-pattern-list>
	
	############################################################################
	# END: BACKOFF RULES
	############################################################################
	
	
	############################################################################
	# BEGIN: BOUNCE RULES
	############################################################################
	
	<bounce-category-patterns>
		/spam/ spam-related
		/junk mail/ spam-related
		/blacklist/ spam-related
		/blocked/ spam-related
		/\bU\.?C\.?E\.?\b/ spam-related
		/\bAdv(ertisements?)?\b/ spam-related
		/unsolicited/ spam-related
		/\b(open)?RBL\b/ spam-related
		/realtime blackhole/ spam-related
		/http:\/\/basic.wirehub.nl\/blackholes.html/ spam-related
		/\bvirus\b/ virus-related
		/message +content/ content-related
		/content +rejected/ content-related
		/quota/ quota-issues
		/limit exceeded/ quota-issues
		/mailbox +(is +)?full/ quota-issues
		/\bstorage\b/ quota-issues
		/(user|mailbox|recipient|rcpt|local part|address|account|mail drop|ad(d?)ressee) (has|has been|is)? *(currently|temporarily +)?(disabled|expired|inactive|not activated)/ inactive-mailbox
		/(conta|usu.rio) inativ(a|o)/ inactive-mailbox
		/Too many (bad|invalid|unknown|illegal|unavailable) (user|mailbox|recipient|rcpt|local part|address|account|mail drop|ad(d?)ressee)/ other
		/(No such|bad|invalid|unknown|illegal|unavailable) (local +)?(user|mailbox|recipient|rcpt|local part|address|account|mail drop|ad(d?)ressee)/ bad-mailbox
		/(user|mailbox|recipient|rcpt|local part|address|account|mail drop|ad(d?)ressee) +(\S+@\S+ +)?(not (a +)?valid|not known|not here|not found|does not exist|bad|invalid|unknown|illegal|unavailable)/ bad-mailbox
		/\S+@\S+ +(is +)?(not (a +)?valid|not known|not here|not found|does not exist|bad|invalid|unknown|illegal|unavailable)/ bad-mailbox
		/no mailbox here by that name/ bad-mailbox
		/my badrcptto list/ bad-mailbox
		/not our customer/ bad-mailbox
		/no longer (valid|available)/ bad-mailbox
		/have a \S+ account/ bad-mailbox
		/\brelay(ing)?/ relaying-issues
		/domain (retired|bad|invalid|unknown|illegal|unavailable)/ bad-domain
		/domain no longer in use/ bad-domain
		/domain (\S+ +)?(is +)?obsolete/ bad-domain
		/denied/ policy-related
		/prohibit/ policy-related
		/rejected/ policy-related
		/refused/ policy-related
		/allowed/ policy-related
		/banned/ policy-related
		/policy/ policy-related
		/suspicious activity/ policy-related
		/bad sequence/ protocol-errors
		/syntax error/ protocol-errors
		/\broute\b/ routing-errors
		/\bunroutable\b/ routing-errors
		/\bunrouteable\b/ routing-errors
		/^2.\d.\d/ success
		/^[45]\.1\.1/ bad-mailbox
		/^[45]\.1\.2/ bad-domain
		/^[45]\.3\.5/ bad-configuration
		/^[45]\.4\.1/ no-answer-from-host
		/^[45]\.4\.2/ bad-connection
		/^[45]\.4\.4/ routing-errors
		/^[45]\.4\.6/ routing-errors
		/^[45]\.4\.7/ message-expired
		/^[45]\.7\.1/ policy-related
		// other    # catch-all
	</bounce-category-patterns>
	
	############################################################################
	# END: BOUNCE RULES
	############################################################################
	
	
	
	
	#
	# Settings per source IP address (for incoming SMTP connections)
	#\n";
			
					$vmta = 0;
					$smtp_user = $virtual_mta = $ip_listner = ''  ;
					
					
					foreach($smtp_list as  $k => $ip)
					{
						if($k<10)
							$source = "0".$k;
						else	
							$source = $k;
						
						
						
						array_push($fdns_list , array('ip' => $smtp_list[$k]['ip'] , 'fdns' =>  'slot'.$source.'.'.$smtp_list[$k]['group']));
						
						$virtual_mta .='<virtual-mta slot'.$source.'.'.$smtp_list[$k]['group'].'>' . "\n";
						$virtual_mta .='	smtp-source-host '.trim($smtp_list[$k]['ip']).' '.gethostbyaddr($smtp_list[$k]['ip'])."\n" ;
						$virtual_mta .='</virtual-mta>'. "\n". "\n";
	
						$virtual_mta .='<virtual-mta-pool cloud'.$source.'.'.$smtp_list[$k]['group'].'>' . "\n";
						$virtual_mta .='	virtual-mta slot'.$source.'.'.$smtp_list[$k]['group']."\n" ;
						$virtual_mta .='</virtual-mta-pool>'. "\n". "\n";
	
	
						$smtp_user .='<smtp-user '.$smtp_list[$k]['username'].'>'."\n";
						$smtp_user .='  	password '.$smtp_list[$k]['password'] ."\n";
						$smtp_user .='  	source {server'.($source).'}'."\n";
						$smtp_user .='</smtp-user>'."\n";
						
						$smtp_user .='<source {server'.($source).'}>'."\n";
						$smtp_user .='    	default-virtual-mta cloud'.$source.'.'.$smtp_list[$k]['group']."\n";
						$smtp_user .='</source>'."\n"."\n";
				
						$ip_listner .= "smtp-listener ".trim($smtp_list[$k]['ip']).":". $ser_info['esp_smtp_port'] . " \n";
					} 
	
					
					
			
					$config  .= $virtual_mta . $smtp_user  ;
			
					if(isset($pmta_config_content['SMTPAUTHS']) && $pmta_config_content['SMTPAUTHS'])
						$config .=  $pmta_config_content['SMTPAUTHS'] . "\n\n";
			
					
					$config  .="
	<source 0/0>
		jobid-header Message-ID 
		process-x-job yes
		hide-message-source yes
		allow-unencrypted-plain-auth yes
		hide-message-source yes
		always-allow-relaying yes   # allow feeding
		add-received-header no
		process-x-virtual-mta yes   # allow selection of a virtual MTA
		max-message-size unlimited  # 0 implies no cap, in bytes
		smtp-service yes            # allow SMTP service
		require-auth true
		add-message-id-header yes
	</source>  \n\n \n\n";
				
					 
	
					$config .= $ip_listner . "\n\n";
	
					if(isset($pmta_config_content['SMTPLISTNER']) &&  $pmta_config_content['SMTPLISTNER'])
						$config .=  $pmta_config_content['SMTPLISTNER'] . "\n\n";
			
					
					$dkim_files = array();
					$config .=  "# DKIM SELECTORS START \n";
					 
					foreach($dkim_list as $k => $val)
					{
							$config .=  "domain-key ".$dkim_list[$k]['selector'].",".trim($dkim_list[$k]['domain']).", /etc/pmta/dkim/".$dkim_list[$k]['selector'].".".trim($dkim_list[$k]['domain']).".pem \n";
							$dkim_files[$dkim_list[$k]['selector'].".".trim($dkim_list[$k]['domain']).".pem"] = $dkim_list[$k]['private_key'];
					}
	
					$config .=  "# DKIM SELECTORS END \n";
					$AOLTopLevel = '$AOLTopLevel'	;
					
					
					if(isset($pmta_config_content['DKIMSELECTOR']) && $pmta_config_content['DKIMSELECTOR'])
						$config .=  $pmta_config_content['DKIMSELECTOR'] . "\n\n";
					
					$config .=  '
	domain-macro HotmailTopLevel co.il, co.jp, com, com.ar, com.br, com.tr,
	co.th, co.uk, de, es, fr, it, jp, se, at, be, ca, cl, cn, co.kr, com.au,
	com.mx, com.my, com.sg, co.za, dk, hk, ie, in, nl, no, ru
	domain-macro YahooTopLevel co.il, co.jp, com, com.ar, com.br, com.tr,
	co.th, co.uk, de, es, fr, it, jp, se, at, be, ca, cl, cn, co.kr, com.au,
	com.mx, com.my, com.sg, co.za, dk, hk, ie, in, nl, no, ru
	domain-macro AOLTopLevel co.il, co.jp, com, com.ar, com.br, com.tr, co.th,
	co.uk, de, es, fr, it, jp, se, at, be, ca, cl, cn, co.kr, com.au, com.mx,
	com.my, com.sg, co.za, dk, hk, ie, in, nl, no, ru
	
	<domain  verifier.port25.com>
		max-smtp-out    200       # max. connections *per domain*
		bounce-after    4d12h    # 4 days, 12 hours
		retry-after     10m      # 10 minutes
		max-msg-rate    10/s
		max-msg-per-connection 5
		dk-sign yes
		dkim-sign yes
		dkim-identity sender-or-from
	
	</domain>
	
	<domain cox.net>
		max-smtp-out    5       # max. connections *per domain*
		bounce-after    4d12h    # 4 days, 12 hours
		retry-after     10m      # 10 minutes
		max-msg-per-connection 100
		dk-sign yes
		dkim-sign yes
		dkim-identity sender-or-from
		
		log-commands    yes
		backoff-to-normal-after 2h
		backoff-to-normal-after-delivery true
		backoff-retry-after 30m
		backoff-max-msg-rate    10/m
		bounce-upon-no-mx yes
		smtp-pattern-list SMTPRESPONS
	</domain>
	
	<domain comcast.net>
		max-smtp-out    2      # max. connections *per domain*
		bounce-after    4d12h    # 4 days, 12 hours
		retry-after     10m      # 10 minutes
		max-msg-per-connection 100
		dk-sign yes
		dkim-sign yes
		dkim-identity sender-or-from
		
		log-commands    yes
		backoff-to-normal-after 2h
		backoff-to-normal-after-delivery true
		backoff-retry-after 30m
		backoff-max-msg-rate    10/m
		bounce-upon-no-mx yes
		smtp-pattern-list SMTPRESPONS
	</domain>
	
	<domain aol.\$AOLTopLevel> 
		max-smtp-out    2       # max. connections *per domain*
		bounce-after    4d12h    # 4 days, 12 hours
		retry-after     10m      # 10 minutes
		max-msg-rate    1500/h
		max-msg-per-connection 100
		smtp-421-means-mx-unavailable   yes
		dk-sign yes
		dkim-sign yes
		dkim-identity sender-or-from
		
		log-commands    yes
		backoff-to-normal-after 2h
		backoff-to-normal-after-delivery true
		backoff-retry-after 30m
		backoff-max-msg-rate    10/m
		bounce-upon-no-mx yes
		smtp-pattern-list SMTPRESPONS
	</domain>
	
	<domain yahoo.\$YahooTopLevel>
		max-smtp-out    3       # max. connections *per domain*
		bounce-after    4d12h    # 4 days, 12 hours
		retry-after     10m      # 10 minutes
		max-msg-rate    2000/h
		max-msg-per-connection 5
		smtp-421-means-mx-unavailable   yes
		dk-sign yes
		dkim-sign yes
		dkim-identity sender-or-from
		
		log-commands    yes
		backoff-to-normal-after 2h
		backoff-to-normal-after-delivery true
		backoff-retry-after 30m
		backoff-max-msg-rate    10/m
		bounce-upon-no-mx yes
		smtp-pattern-list SMTPRESPONS
	</domain>
	
	<domain hotmail.\$HotmailTopLevel>
		max-smtp-out    25       # max. connections *per domain*
		
		bounce-after    2d12h    # 4 days, 12 hours
		retry-after     10m      # 10 minutes
		max-msg-rate    250/min
		max-msg-per-connection 150
		smtp-421-means-mx-unavailable   yes
		dk-sign yes
		dkim-sign yes
		dkim-identity sender-or-from
		
		log-commands    yes
		backoff-to-normal-after 2h
		backoff-to-normal-after-delivery true
		backoff-retry-after 30m
		backoff-max-msg-rate    25/min
		bounce-upon-no-mx yes
		smtp-pattern-list SMTPRESPONS
	</domain>
	
	<domain msn.com>
		max-smtp-out    5       # max. connections *per domain*
		bounce-after    4d12h    # 4 days, 12 hours
		retry-after     10m      # 10 minutes
		max-msg-per-connection 10
		smtp-421-means-mx-unavailable   yes
		dk-sign yes
		dkim-sign yes
		dkim-identity sender-or-from
		
		log-commands    yes
		backoff-to-normal-after 2h
		backoff-to-normal-after-delivery true
		backoff-retry-after 30m
		backoff-max-msg-rate    10/m
		bounce-upon-no-mx yes
		smtp-pattern-list SMTPRESPONS
	</domain>
	
	<domain gmail.com>
		max-smtp-out    5       # max. connections *per domain*
		bounce-after    2d12h    # 4 days, 12 hours
		retry-after     30m      # 10 minutes
		dk-sign yes
		dkim-sign yes
		dkim-identity sender-or-from
		
		max-msg-rate    5000/h
		max-msg-per-connection 250
		smtp-421-means-mx-unavailable   yes
		log-commands    yes
		backoff-to-normal-after 2h
		backoff-to-normal-after-delivery true
		backoff-retry-after 30m
		backoff-max-msg-rate    500/h
		bounce-upon-no-mx yes
		smtp-pattern-list SMTPRESPONS
	</domain>
	
	
	#
	# {gmImprinter} is a special queue used for imprinting Goodmail tokens.
	#
	<domain {gmImprinter}>
		max-events-recorded 150
		log-messages yes
		log-data no             # extremely verbose, for debugging only
		retry-after 15s
	</domain>
	
	<domain *>
		max-smtp-out    2       # max. connections *per domain*
		bounce-after    4d    # 4 days, 12 hours
		retry-after     10m      # 10 minutes
		max-msg-per-connection 1
		dk-sign yes
		dkim-sign yes
		dkim-identity sender-or-from
		
		log-commands    yes
		backoff-to-normal-after 2h
		backoff-to-normal-after-delivery true
		backoff-retry-after 30m
		backoff-max-msg-rate    10/m
		bounce-upon-no-mx yes
		smtp-pattern-list SMTPRESPONS
		#     remove-header X-Priority,X-Report-Abuse,X-Spam-Score,X-Spam-Status,X-Spam-Bar,X-Ham-Report,X-Spam-Flag
	</domain>
	
	#
	# Goodmail imprinter configuration
	#
	#<gm-imprinter>
	#    account-id ID               # replace with value from mailcenter
	#    imprinter-id ID             # replace with value from mailcenter
	#    imprinter-password PW       # replace with value from mailcenter
	#
	#    # If the directives below are not specified, defaults are picked as
	#    # described in the Goodmail documentation
	#
	#    default-token-class 1       # optionally set as appropriate
	#    default-content-type 1      # optionally set as appropriate
	#    default-payer-id ID         # optionally set as appropriate
	#    default-obo-id ID           # optionally set as appropriate
	#</gm-imprinter>
	
	#
	# Port used for HTTP management interface
	#
	http-mgmt-port 8080
	
	#
	# IP addresses allowed to access the HTTP management interface, one
	# per line
	#
	
	
	#
	# Synchronize I/O to disk after receiving the message.  \'false\' yields
	# higher performance, but the message may be lost if the system crashes
	# before it can write the data to disk.
	#
	sync-msg-create false
	
	#
	# Synchronize I/O to disk after updating the message (e.g., to mark recipients
	# handled).  \'false\' yields higher performance, but if the system crashes
	# before it can write the data to disk, some recipients may receive multiple
	# copies of a message.
	#
	run-as-root yes
	sync-msg-update false
	
	#
	# Logging file
	#
	log-file /etc/pmta/log/pmta.log # logrotate is used for rotation
	log-rotate 365                 # number of files; 0 disables rotation
	
	#
	# Accounting file(s)
	#
	<acct-file /etc/pmta/files/acct.csv>
	#    move-to /opt/myapp/pmta-acct   # configure as fit for your application
	record-fields delivery *,envId,jobId,bounceCat
	move-interval 5m
	delete-after 7d
	max-size 50M
	user-string from
	</acct-file>
	
	# transient errors (soft bounces)
	<acct-file /etc/pmta/files/diag.csv>
	move-interval 1d
	delete-after 7d
	records t
	</acct-file>
	
	#
	# Spool directories
	#
	spool /var/spool/pmta
	
	#<spool /var/spool/pmta>
	#    deliver-only no
	#</spool>
	# EOF
	
	host-name '.$ser_info['esp_smtp_host'];
		
				
		 
				//print nl2br(htmlentities($config));
				//die();
			
			 
				define('NET_SSH2_LOGGING', NET_SSH2_LOGGING_COMPLEX);
				 
				//print "Connecting  ".$ser_info['esp_server_ip']." <br>";
				$ssh = new Net_SSH2($ser_info['esp_server_ip'] , $ser_info['esp_ssh_port']?$ser_info['esp_ssh_port']:22);
				$ssh->setTimeout(5);
				//print "send Login  <br>";
				if (!$ssh->login($ser_info['esp_server_username'], $ser_info['esp_server_password'])) {
					print ('Login Failed');
					return;
				}
				
				
				$response =  "Login Successfull \n"; 
				$response .= $ssh->exec('rm /etc/pmta/config2');
				$response .= $ssh->exec('echo "'.$config.'" > /etc/pmta/config2');
				
				foreach($dkim_files as $file => $content)
				{
					$response .= $ssh->exec('rm /etc/pmta/dkim/'.$file);
					$response .= $ssh->exec('echo "'.$content.'" > /etc/pmta/dkim/'.$file);
				}
				
	 
				
				$response .= $ssh->exec('/etc/init.d/pmta restart');
				$response .= $ssh->exec('/etc/init.d/pmtahttp restart');
				//$response .= $ssh->exec('/etc/init.d/pmtahttp restart');
				$response .= $ssh->exec('exit');
				//$response .= $ssh->exec('exit');
				 
				return $fdns_list;
				 
				
				
		
	
	}

	$q = "SELECT * ,    AES_DECRYPT(esp_server_password,'ED20D21A390D9441B83F3371C3229A0B' ) as esp_server_password FROM esp_server ";
	$r = mysql_query($q) or die(mysql_error());
	$ser_info = mysql_fetch_assoc($r);
	
	$smtp_list = $domains  = array();
	$key = 0;
	$q = "select * FROM smtp";
	$r = mysql_query($q) or die(mysql_error());
	while($row = mysql_fetch_assoc($r))
	{
	
		$smtp_list[$key]['group'] 			= trim($row['smtp_group_name']);
		$smtp_list[$key]['host'] 			= trim($row['smtp_host']);
		$smtp_list[$key]['port'] 			= trim($row['smtp_port']);
		$smtp_list[$key]['ip'] 				= trim($row['smtp_pool']);
		$smtp_list[$key]['username'] 		= trim($row['smtp_username']);
		$smtp_list[$key]['password']		= trim($row['smtp_password']);
		array_push($domains , $smtp_list[$key]['group']);
		$key++;
 	}
	
	$domains  = array_unique($domains);
	$dkim_list = array();
	foreach($domains as $k => $dom)
	{
		$dkim_list[$k]['selector'] 		= 'private'; 
		$dkim_list[$k]['private_key'] 	= '-----BEGIN RSA PRIVATE KEY-----
MIICXQIBAAKBgQC9cTfuyGEPs2AKN+mrxcEmMVNMaHf2ipB+Ft+1KC8WhIfiLyVt
sdv2npnlUJQSKU1sVHdcRWUvn8bDoe1IzI35mJW9CutaHltC8FZzdQA0ahAVgVOg
k/Hhbfo8/9QIG22eVfsvkJhLHINMqdJDlhgCeBeoZa7V/TUF0wMRQ7DGEwIDAQAB
AoGAS0k8XkP59uBlcYoo7h/oE8KUhRAbZLpKlCGJBBiVJhDDY8syx7ZgYVFEfdKZ
FeKeJ0gmK2BUxylrN4IZp83LOc6RrDQWUbYyyr7Bciu+0zX7PThKu6zvtosIk0Lh
RYfJ4/U3cNW0Mb/Dy9vJ/kSV8Ssd5ImqTRUxXm/A8JEzAzkCQQD8fED3ezPFI490
snpVHVUmX+Ur+3sXYrAT5GcynqkE3RsAzPP6M1B2RaOhGcuXFO5eiQBeKGaI4+lW
a0/i0GaXAkEAwBROlEX5lN2eGWV4aPnLSyJhvT+nDDfgNHpTTjDqMIdp6dyzFDUJ
tUEiOLzF+ZCKvKvXrPOAg01Fj7x71fwn5QJBANvlg9eGz8HkhK6IOw8iKuTvI/M4
ZS4q31uT02U81cvMnhYGan8AbhVz9Vz70FrW+fwPqehXm2WhyblgYQUTqCUCQGkY
82H347cLh90Xg0nVG+IRfu9A69Moo5mzMO/AnfNNtKsMYkP1PUmayPHIgH6sEu1n
DUyJs9CkMCKUnCGape0CQQCs5hXIQoVDb86fXXNYy8d7aFAWY2Jj+gRe/xzvFsOZ
Gfjij1xKC1cLUL5Q9a+SGRdw/q+8Uc3/GMzWh97Icgs8
-----END RSA PRIVATE KEY-----'; 
		$dkim_list[$k]['domain'] 		= $dom;
	
	}
	set_include_path('modules/phpseclib');
	include('Net/SSH2.php');
	$fdns_list = generatePMTAServerConfig($ser_info , $ip_list , $smtp_list , $dkim_list );
	print_r($fdns_list);
?>
