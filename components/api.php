<!-- available jobs API -->
<?php
	$curl = curl_init();
	// $awards = get_field( 'awards' );

	curl_setopt_array($curl, array(
	CURLOPT_URL => "https://api.homerun.co/v1/jobs",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_POSTFIELDS =>"{\n    \"contact\": {\n        \"email\": \"victor@viamsterdam.com\"\n    }\n}",
	CURLOPT_HTTPHEADER => array(
		"YOUR_API_KEY: jM5G1NlcvwA6hjjRJL5SqPoHt1cVmtZ1",
		"Content-Type: application/json",
		"Authorization: Bearer jM5G1NlcvwA6hjjRJL5SqPoHt1cVmtZ1"
	),
	));

	$response = curl_exec($curl);

	curl_close($curl);
	$jobs =  json_decode($response);

	if(!empty($jobs->data)) :

		$open_jobs = false;

		foreach($jobs->data as $job):
			if($job->status === 'open') :
				$open_jobs = true;
				break;
			endif;
		endforeach;

	

	endif;
	?>