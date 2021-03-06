<?php
include_once("Http.class.php");
include_once("Error.class.php");

class YoukuSearcher
{

	const SEARCH_VIDEO_URL	 = "https://openapi.youku.com/v2/searches/video/by_keyword.json";

	const REFRESH_FILE = "refresh.txt";

	private $client_id;
	private $client_secret;

	public function __construct($client_id, $client_secret) { 
		$this->client_id = $client_id;
		$this->client_secret = $client_secret;
	}

	public function searchYoukuData($params) {
        
        $parameter = array (
			"client_id"     => $this->client_id,
			"keyword" 		=> $params['word'],
			"category"		=> $params['category'],
			"period"		=> $params['period'],
			"orderby"		=> $params['orderby'],
			"page"			=> $params['page'],
			"count"			=> $params['count'],
		);
        
        try {
			$result = json_decode(Http::get(self::SEARCH_VIDEO_URL, $parameter));
			if (isset($result->error)) {
				$error = $result->error;
				throw new UploadException($error->description,$error->code);
			}
		} catch (UploadException $e) {
			echo $e->getError();
			exit;
		}

		return $result;
	}
}
?>
