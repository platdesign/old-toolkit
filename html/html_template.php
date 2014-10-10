<?PHP
class html_template {
	
	function article($doc,$con_limit=null) {
		$doc = (object) $doc;
		
		if(isset($doc->link)) {
			$title = html::h(
				html::a($doc->title,array("href"=>$doc->link))
			);
		} else {
			$title = html::h($doc->title);
		}
		
		if(isset($con_limit)) {
			$con_length = strlen($doc->content);
			if($con_length > $con_limit) {
				$content = substr($doc->content,0,$con_limit);
				$content = substr($content,0,strripos($content," "));
			} else {
				$content = $doc->content;
			}
			$content .= html::a("[...]",array("href"=>$doc->link,"class"=>"more"));
		} else {
			$content = $doc->content;
		}
		
		return
			html::article(
				html::header(
					$title.
					self::createDate($doc->createTS).
					self::author($doc->author)
				).
				html::p(
					$content
				)
				,array("class"=>(isset($con_limit))?"teaser":NULL)
			);
	}
	
	function author($author) {
		if( !empty($author) ) {
			return html::address( $author , array("class"=>"author") );
		}
	}
	
	function createDate($date,$format="d.m.Y //  H:i") {
		if( !empty($date) ) {
			if(is_int($date)) {
				$pubdate = date("Y-m-d",$date);
				$labelDate = date($format,$date);
			} else {
				$pubdate = $labelDate = $date;
			}
			
			return html::time( $labelDate , array("pubdate"=>"pubdate","datetime"=>$pubdate,"title"=>$labelDate) );
		}
	}



	
	
	function page($array){
		$page = (object) $array;
		
		return 
			html::doctype().
			html::openTag("html",array("prefix"=>"og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#")).
				html::head(
					html::title($page->title).
					self::page_metas($page).
					self::page_links($page).
					$page->head
				).
				html::body(
					$page->body
				).
			html::closeTag("html");
	}

	function page_metas($doc) {
		
		if(!$doc->robots) { $doc->robots = "index"; }
		
		if($doc->noStd == false) {
			$return = 
				html::meta("charset").
				html::meta("viewport").
				html::meta("ie");

			if($doc->robots) {
				$return .=
				html::meta("robots",$doc->robots);
			}
		}
		
		if($doc->keywords) {
			$return .= html::meta("keywords",$doc->keywords);
		}
		
		if($doc->author) {
			$return .= html::meta("author",$doc->author);
		}
		
		if($doc->description) {
			$return .=
			html::meta("description",$doc->description).
			html::meta("og",array("description" => $doc->description));
		}
		
		if($doc->title) {
			$return .= html::meta("og",array("title" => $doc->title));
		}
		
		if($doc->url) {
			$return .= html::meta("og",array("url" => $doc->url));
		}
		
		if($doc->logo) {
			$return .= html::meta("og",array("image" => $doc->logo));
		}
		
		if($doc->type) {
			$return .= html::meta("og",array("type" => $doc->type));
		}
		
		if($doc->site_name) {
			$return .= html::meta("og",array("site_name" => $doc->site_name));
		}
		
		if($doc->article) {
			$article = $doc->article;
			
			$return .= html::meta("og",array("type" => "article"));
			if($article->createTS) {
				$return .= html::openTag("meta",array("property"=>"article:published_time", "content"=>date("Y-m-d",$article->createTS)),TRUE);
			}
			if($article->updateTS) {
				$return .= html::openTag("meta",array("property"=>"article:modified_time", "content"=>date("Y-m-d",$article->updateTS)),TRUE);
				
			}
			if($article->author) {
				//$return .= html::openTag("meta",array("property"=>"article:author", "content"=>$article->author),TRUE);
			}
		}
		
		return $return;
	}
	
	function page_links($doc) {
		
		if($doc->logo) {
			$return .= html::link(array("rel"=>"apple-touch-icon","href"=>$doc->logo));
		}
		
		if($doc->icon) {
			$return .= html::link(array("rel"=>"icon","href"=>$doc->favicon,"type"=>"image/x-icon"));
			$return .= html::link(array("rel"=>"shortcut icon","href"=>$doc->favicon,"type"=>"image/x-icon"));
		}

		return $return;
	}
	
}
?>