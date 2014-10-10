<?PHP

class arr {
	
	function sortby($array,$key,$desc=FALSE) {
		if(is_array($array)) {
			$sorter = function($key) {
				return function ($a, $b) use ($key) {
					return strnatcmp($a->{$key}, $b->{$key});
				};
			};
			
			if($key) {
				usort($array, $sorter($key));
			}
			if($desc) {
				$array = array_reverse($array);
			}
		}
		
		return $array;
	}
	
	
}

?>