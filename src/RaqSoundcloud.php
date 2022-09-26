<?php  

class RaqSoundcloud {

	public $client_id;
	public $track_id;
	public $track_information;
	public $track;
	public $html;
	public $dom;
	public $stream;
	public $search_results;

	public $track_api_endpoint = "https://api-v2.soundcloud.com/tracks?ids=";
	public $app_version = "&app_version=1663668775&app_locale=en_US";

	// search
	public $endpoint_search = "https://api-v2.soundcloud.com/search/tracks";
	public $limit_offset = "&limit=20&offset=0";
	// public $app_version = "&app_version=1663668775&app_locale=en";



	public function request($url)
	{
		$html = file_get_contents($url);

		$this->html = $html;

		libxml_use_internal_errors(true);
		$dom = new DomDocument;
		$dom->loadHTML($html);

		$this->dom = $dom;

		return $this;
	}

	public function getClientId()
	{
		
		if(!$this->dom){
			$this->request("https://soundcloud.com/");
		}

		$scripts = $this->dom->getElementsByTagName('script');
		$script = $scripts->item(13)->getAttribute('src');

		$script = file_get_contents($script);
		$script = explode('"client_id=', $script)[1];
		$script = explode('")', $script)[0];

		$this->client_id = $script;
	}

	public function getTrackId()
	{
		$metas = $this->dom->getElementsByTagName('meta');

		foreach ($metas as $meta) {
			$content_id = $meta->getAttribute('content');

			if(strpos($content_id, "soundcloud://sounds:") !== false){
				$track_id = str_replace("soundcloud://sounds:", "", $content_id);	
			}
		}

		$this->track_id = $track_id;

		return $this;
	}

	public function getTrackInformation()
	{

		$file = $this->track_api_endpoint.$this->track_id."&client_id=".$this->client_id.$this->app_version;
		$file = file_get_contents($file);
		$track_information = json_decode($file);

		if(!$track_information[0]->artwork_url){
			$track_information[0]->artwork_url = $track_information[0]->user->avatar_url;
		}

		$track = new StdClass;
		$track->artwork_url = $track_information[0]->artwork_url;
		$track->artwork_url_hd = @str_replace('large', 't500x500', $track_information[0]->artwork_url);
		$track->caption = $track_information[0]->caption;
		$track->duration = $track_information[0]->duration;
		$track->genre = $track_information[0]->genre;
		$track->id = $track_information[0]->id;
		$track->id = $track_information[0]->id;
		$track->permalink_url = $track_information[0]->permalink_url;
		$track->title = $track_information[0]->title;
		$track->user_id = $track_information[0]->user_id;
		$track->waveform_url = $track_information[0]->waveform_url;
		$track->media = $track_information[0]->media;
		$track->user = $track_information[0]->user;

		$this->track = $track;

		$this->track_information = $track_information;

		return $this;
	}

	public function getStream()
	{

		$stream_url = $this->track_information[0]->media->transcodings[1]->url;
		$stream_url = $stream_url."?client_id=".$this->client_id;

		$stream_url = file_get_contents($stream_url);
		$stream_url = json_decode($stream_url)->url;

		$this->stream_url = $stream_url;

		return $this;
	}

	public function search($q)
	{
		$q = urlencode($q);
		$endpoint_search = $this->endpoint_search."?q=".$q."&client_id=".$this->client_id.$this->limit_offset.$this->app_version;
		
		$file = file_get_contents($endpoint_search);
		$file = json_decode($file);

		$collections = $file->collection;;
		$result = [];

		foreach ($collections as $collection) {
			$progressive = array_filter($collection->media->transcodings,  function ($item){
				return $item->format->protocol == "progressive";
			});
			
			if($progressive){
				$progressive = array_values($progressive); // reorder arrays
				$progressive = $progressive[0]; // get [0] array

				$r = new StdClass;
				$r->artwork_url = $collection->artwork_url;
				$r->title = $collection->title;
				$r->full_duration = $collection->full_duration;
				// $r->permalink_url = $collection->permalink_url;
				// $r->waveform_url = $collection->waveform_url;
				$r->progressive = $progressive->url;

				array_push($result, $r);
			}

		}

		$this->search_result = $result;

		return $this;
	}
	
	public function getStreamProgressive($progressive)
	{
		$progressive = $progressive."?client_id=".$this->client_id;

		$file = file_get_contents($progressive);
		$file = json_decode($file);

		$this->stream = $file->url;

		return $this;
	}


}


?>