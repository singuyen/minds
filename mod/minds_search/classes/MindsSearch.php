<?php

class MindsSearch {

	function __construct() {
		$this -> server = $server;
	}

	function services() {
		return minds_search_return_services();
	}

	function search($q,$types = array('all'),$services = array('all'), $limit=10, $page=1) {
		$servicesCount = count($this->services());
		$serviceLimit = round($limit / $servicesCount);
		foreach ($this->services() as $service) {
			if(in_array($service->name, $services)){
				$classname = $service->classname;
			} elseif(in_array('all', $services)){
				$classname = $service->classname;
				$limit = $serviceLimit;
			} else{
				continue;//skip if not states or all
			}
			$class = new $classname();
			$data[] = $class->query($q, $limit, $page);
		}
		return $this->mergeData($data);;
		//return $data;
	}
	
	function get($url){
		$rsp = file_get_contents($url);
		return $rsp;
	}
	
	function push($url){
		$data = file_get_contents($url, NULL, stream_context_create(array('http' => 'PUSH')));
		return $data;
	}
	
	function mergeData($data){
		$count = count($data);
		$photos = array();
		$videos = array();
		$sounds = array();
		foreach($data as $k=>$v){
			if($data[$k]->photos){
				$photos = array_merge($photos,$data[$k]->photos);
			}
			if($data[$k]->videos){
				$videos = array_merge($videos,$data[$k]->videos);
			}
			if($data[$k]->sounds){
				$sounds = array_merge($sounds,$data[$k]->sounds);
			}
		}
		$return['photos'] = $photos;
		$return['videos'] = $videos;
		$return['sounds'] = $sounds;
		return $return;
	}
	
	
	function findLicense($code){
		$cc_by = 'attribution-cc';
		$cc_by_sa = 'attribution-sharealike-cc';
		$cc_by_nd = 'attribution-noderivs-cc';
		$cc_by_nc = 'attribution-noncommerical-cc';
		$cc_by_nc_sa = 'attribution-noncommercial-sharealike-cc';
		$cc_by_nc_nd = 'attribution-noncommercial-noderivs-cc';
		$cc0 = 'publicdomaincco';
		
		switch($code){
			//flickr
			case 1:
				return $cc_by_nc_sa;
				break;
			case 2:
				return $cc_by_nc;
				break;
			case 3:
				return $cc_by_nc_nd;
				break;
			case 4:
				return $cc_by;
				break;
			case 5:
				return $cc_by_sa;
				break;
			case 6:
				return $cc_by_nd;
				break;
			case 7:
				return $cc0;
				break;
			//ccmixter
			case 'Attribution (3.0)':
				return $cc_by;
				break;
			case 'Attribution Noncommercial  (3.0)':
				return $cc_by_nc;
				break;
			case 'Attribution Noncommercial Share-Alike  (3.0)':
				return $cc_by_nc_sa;
				break;
			case 'Attribution Share-Alike  (3.0)':
				return $cc_by_sa;
				break;
			case 'CC0 (CC Zero)':
				return $cc0;
				break;
			//soundcloud
			case 'cc-by':
				return $cc_by;
				break;
			case 'cc-by-sa':
				return $cc_by_sa;
				break;
			case 'cc-by-nd':
				return $cc_by_nd;
				break;
			case 'cc-by-nc':
				return $cc_by_nc;
				break;
			case 'cc-by-nc-sa':
				return $cc_by_nc_sa;
				break;
			case 'cc-by-nc-nd':
				return $cc_by_nc_nd;
				break; 	
			//youtube
			case 'cc':
				return $cc_by;
				break;
			//archive dot org
			case 'http://creativecommons.org/licenses/by/2.5/':
				return $cc_by;
				break;
			case 'http://creativecommons.org/licenses/by-sa/2.5/':
				return $cc_by_sa;
				break;
			case 'http://creativecommons.org/licenses/by-nd/2.5/':
				return $cc_by_nd;
				break;
			case 'http://creativecommons.org/licenses/by-nc/2.5/':
				return $cc_by_nc;
				break;
			case 'http://creativecommons.org/licenses/by-nc-sa/2.5/':
				return $cc_by_nc_sa;
				break;
			case 'http://creativecommons.org/licenses/by-nc-nd/2.5/':
				return $cc_by_nc_nd;
				break;
			case 'http://creativecommons.org/licenses/publicdomain/':
				return $cc0;
				break;
		}
	}
	
	function renderData() {
		if (!is_array($this->data)) {
			$this->data = array();
		}
	}

}
