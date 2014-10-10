<?PHP
class html {
	
	private static function attr($key,$val=null) {
		if(isset($val)) {
			$val = trim($val);
			return(' '.$key.'="'.$val.'"');
		}
	}
	public static function tag($tagname,$inner=null,$attr=null,$specialOpt=null) {
		$return = 
			self::openTag($tagname,$attr,FALSE,$specialOpt).
			$inner.
			self::closeTag($tagname);
		return($return);
	}
	public static function openTag($tagname,$attr=null,$onlyOpen=FALSE,$sepcialOpt=NULL) {
		$attribute = '';
		if(is_array($attr)) {
			
			foreach($attr as $key => $val) {
				$attribute .= self::attr($key,$val);
			}
		}
		if($onlyOpen == TRUE) { $slash = " /";} else { $slash = ''; }
		$return = '<'.$tagname.$attribute.$slash.$sepcialOpt.'>';
		return($return);
	}
	public static function closeTag($tagname) {
		$return = '</'.$tagname.'>';
		return($return);
	}

	public static function h($inner,$nr=1,$attr=null) {
		$return = self::tag('h'.$nr, $inner, $attr);
		return($return);
	}

	public static function a($inner,$attr=null) {
		if(!isset($attr['href'])) { $attr['href'] = NULL; } else {
			if(is_array($attr['href'])) {
				$attr['href'] = "?".urldecode(http_build_query($attr['href']));
			}
		}
		$return = self::tag('a', $inner, $attr);
		return($return);
	}

	public static function br($anz=1) {
		$return = '';
		for($lfn=0;$lfn<$anz;$lfn++) {
			$return .= self::openTag("br",NULL,TRUE);
		}
		return($return);
	}
	
	public static function hr($anz=1,$attr=null) {
		$return = NULL;
		for($lfn=0;$lfn<$anz;$lfn++) {
			$return .= self::openTag("hr",$attr,TRUE);
		}
		return($return);
	}
	
	public static function pre($inner=null,$attr=array()) {
		$return = self::openTag("pre",$attr).$inner.self::closeTag("pre");
		return($return);
	}
	
	public static function div($inner=null,$attr=null) {
		$return = self::tag('div', $inner, $attr);
		return($return);
	}
	
	public static function span($inner,$attr=null) {
		$return = self::tag('span', $inner, $attr);
		return($return);
	}
	
	public static function p($inner,$attr=null) {
		$return = self::tag('p', $inner, $attr);
		return($return);
	}
	
	public static function b($inner,$attr=null) {
		$return = self::tag('b', $inner, $attr);
		return($return);
	}
	
	public static function i($inner,$attr=null) {
		$return = self::tag('i', $inner, $attr);
		return($return);
	}

	public static function u($inner,$attr=null) {
		$return = self::tag('u', $inner, $attr);
		return($return);
	}
	
	
	public static function script($inner,$attr=array(),$type="text/javascript") {
		$attr['type'] 	= 	$type;
		$attr['charset']=	"utf-8";
		
		$return = self::openTag("script",$attr).$inner.self::closeTag("script");
		return($return);
	}

	public static function noscript($inner) {
		$return = self::tag("noscript",$attr);
		return($return);
	}

	
	public static function link($attr=array()) {
		return self::openTag("link",$attr);
	}
	
	public static function scriptLink($URI,$type="text/javascript") {
		if(is_array($URI)) {
			foreach($URI as $link) {
				$attr['src']	=	$link;
				$attr['type']	=	$type;
				$attr['charset']=	"utf-8";

				$return .= self::openTag("script",$attr).self::closeTag("script");
			}
		} else {
			$attr['src']	=	$URI;
			$attr['type']	=	$type;
			$attr['charset']=	"utf-8";

			$return = self::openTag("script",$attr).self::closeTag("script");
		}
		
		return($return);
	}

	public static function style($inner,$type="text/css",$media="screen") {
		$attr['type']	=	$type;
		$attr['media']	=	$media;
		
		$return = self::openTag("style",$attr).$inner.self::closeTag("style");
		return($return);
	}
	
	public static function cssLink($URI,$type="text/css",$media="screen") {
		if(is_array($URI)) {
			foreach($URI as $link) {
				$attr['charset']=	"utf-8";
				$attr['rel']	=	"stylesheet";
				$attr['href']	=	$link;
				$attr['type'] 	= 	$type;
				$attr['media'] 	= 	$media;

				$return .= self::openTag("link",$attr);
			}
		} else {
			$attr['charset']=	"utf-8";
			$attr['rel']	=	"stylesheet";
			$attr['href']	=	$URI;
			$attr['type'] 	= 	$type;
			$attr['media'] 	= 	$media;

			$return = self::openTag("link",$attr);
		}
		
		
		
		return($return);
	}
	
	public static function meta($type, $content=null) {
		switch($type) {
			case "og":
				$return = self::openTag("meta",array("property"=>"og:".key($content), "content"=>$content[key($content)]),TRUE);
			break;
			case "robots":
				$return = self::openTag("meta",array("name"=>"robots", "content"=>$content),TRUE);
			break;
			case "description":
				$return = self::openTag("meta",array("name"=>"description", "content"=>$content),TRUE);
			break;
			case "keywords":
				$return = self::openTag("meta",array("name"=>"keywords", "content"=>$content),TRUE);
			break;
			case "author":
				$return = self::openTag("meta",array("name"=>"author", "content"=>$content),TRUE);
			break;
			case "charset":
				if(!$content) { $content = "UTF-8"; }
				$return = self::openTag("meta",array("charset"=>$content),TRUE);
			break;
			case "viewport":
				$return = self::openTag("meta",array(
					"name"		=>	"viewport",
					"content"	=>	"width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0",
				),TRUE);
			break;
			case "ie":
				$return = self::openTag("meta",array(
					"http-equiv"	=>	"X-UA-Compatible",
					"content"		=>	"IE=edge,chrome=1"
				),TRUE);
			break;
		}
		return($return);
	}
	
	public static function head($inner,$attr=null) {
		$return = self::tag("head",$inner,$attr);
		return($return);
	}

	public static function title($inner,$attr=null) {
		$return = self::tag("title",$inner,$attr);
		return($return);
	}
	
	
	public static function body($inner,$attr=null) {
		$return = self::tag("body",str_replace(array("\n","\t"),"",$inner),$attr);
		return($return);
	}
	
	public static function audio($inner,$attr=null) {
		if(!$attr['controls']) { $attr['controls'] = "controls"; }
		
		$return = self::openTag("audio",$attr);

			if(is_array($files)) {
				foreach($files as $key => $val) {
					$subattr["src"] = $val['src'];
					$subattr["type"] = $val['type'];
					$return .= self::openTag("source", $subattr,TRUE);
				}
			}
		
		$return .= self::flashAudio($files,$attr);
		$return .= self::closeTag("audio");
		return($return);
		
	}
	public static function video($files,$attr=null) {
		if(!$attr['width']) { $attr['width'] = 320; }
		if(!$attr['height']) { $attr['height'] = 240; }
		if(!$attr['controls']) { $attr['controls'] = "controls"; }
		
		$return = self::openTag("video",$attr);

			if(is_array($files)) {
				foreach($files as $key => $val) {
					$subattr["src"] = $val['src'];
					$subattr["type"] = $val['type'];
					$return .= self::openTag("source", $subattr,TRUE);
				}
			}
		
		$return .= self::flashVideo($files,$attr);
		$return .= self::closeTag("video");
		return($return);
	}
	
	public static function flashVideo($files,$attr=null) {
		$return = 'Flash anstelle von HTML5-Video-Tag!';
		return($return);
	}
	public static function flashAudio($files,$attr=null) {
		$return = 'Flash anstelle von HTML5-Audio-Tag!';
		return($return);
	}
	
	public static function doctype($type=null) {
		return('<!DOCTYPE html>');
	}
	

	private static function getSpaltenOfTable($data) {
		$spaltenAnz = 0;
		foreach($data as $key => $val) {
			$anz = count((array)$val);
			if($anz>$spaltenAnz) {
				$spaltenAnz = $anz;
			}
		}
		return($spaltenAnz);
	}
	public static function td($inner,$attr=null) {
		$return = self::tag('td', $inner, $attr);
		return($return);
	}
	public static function tr($inner,$attr=null) {
		$return = self::tag('tr', $inner, $attr);
		return($return);
	}
	public static function trs($data,$tdattr=array()) {
		
		if(($data)) {
			
			$spaltenAnz = self::getSpaltenOfTable($data);
			$datatrs = NULL;
			foreach($data as $rowKey => $rowData) {
				if($rowData) {
					$datatds = NULL;
					$spalte  = NULL;
					$spalten = count((array) $rowData);
					
					foreach($rowData as $colKey => $colData) {
						$spalte++;
						
						if($spalten < $spaltenAnz AND $spalte == $spalten) {
							$tdattr[$colKey]['colspan'] = $spaltenAnz+1 - $spalten;
						}
						
						if(isset($tdattr[$colKey])) {
							$datatds .= self::td($colData,$tdattr[$colKey]);
						} else {
							$datatds .= self::td($colData);
						}
						
						
					}
				}
				$datatrs .= self::tr($datatds);
			}
		}
		
		return($datatrs);
	}
	public static function table($data,$head=null,$attr=null,$tdattr=array()) {
		$headtr = NULL;
		$datatrs = NULL;
		
		if(is_array($head)) {
			$headtds=NULL;
			foreach($head as $key => $val) {
				if(isset($tdattr[$key])) {
					$headtds .= self::td($val,$tdattr[$key]);
				} else {
					$headtds .= self::td($val);
				}
				
			}
			$headtr = self::tr($headtds,array("class"=>"head"));
		}
		
		
		$datatrs = self::trs($data,$tdattr);

		$return = self::tag("table",$headtr.$datatrs,$attr);

		return($return);
	}
	
	public static function li($inner,$attr=null) {
		$return = self::tag('li', $inner, $attr);
		return($return);
	}
	public static function ul($inner,$attr=null) {
		$return = self::tag('ul', $inner, $attr);
		return($return);
	}
	public static function listing($data,$attr=null) {
		if(is_array($data)) {
			$lis = NULL;
			foreach($data as $key => $val) {
					if(is_array($val)) {
						$lis .= self::listing($val);
					} else {
						$lis .= self::li($val);
					}
			}
		}
		$return = self::ul($lis,$attr);
		return($return);
	}

		public static function form($inner,$attr=null) {
			if(!isset($attr['method'])) { $attr['method'] = "POST"; }
			$return = self::tag("form",$inner,$attr);
			return($return);
		}

		public static function fieldset($legend,$inner=null,$attr=array()) {
			if($legend != NULL) {
				$legend = self::legend($legend);
			}
			$return = self::tag("fieldset",$legend.$inner,$attr);
			return($return);
		}

		public static function legend($inner,$attr=array()) {
			return html::tag("legend",$inner,$attr);
		}

		public static function label($inner,$attr=array()) {
			return html::tag("label",$inner,$attr);
		}

		public static function input($attr=array()) {
			return html::openTag("input",$attr,TRUE);
		}

		public static function select($options=array(),$attr=array()) {

			if($attr['placeholder']) {
				$inner = html::option($attr['placeholder'],array("value"=>"","disabled"=>"disabled","selected"=>"selected"));
				unset($attr['placeholder']);
			}

			if($options) {
				foreach($options as $key => $val) {

					if( is_array($val) ) {

						$inner .= html::openTag("optgroup",array("label"=>$key));

						foreach($val as $subkey => $subval) {
							if($subkey == $attr['value'] AND isset($attr['value'])) {
								$inner .= self::option($subval,array("value"=>$subkey,"selected"=>"selected"));
							} else {
								$inner .= self::option($subval,array("value"=>$subkey));
							}
						}

						$inner .= html::closeTag("optgroup");

					} else {
						if($key == $attr['value'] AND isset($attr['value'])) {
							$inner .= self::option($val,array("value"=>$key,"selected"=>"selected"));
						} else {
							$inner .= self::option($val,array("value"=>$key));
						}
					}


				}
			}
			unset($attr['value']);
			return html::tag("select",$inner,$attr);
		}

		public static function textarea($inner,$attr=array()) {
			return html::tag("textarea",$inner,$attr);
		}

		public function option($inner,$attr=array(),$selected = FALSE) {
			if($selected == TRUE) {
				$specialOpt = " SELECTED";
			}
			return html::tag("option",$inner,$attr,$specialOpt);
		}

		public function options($options,$selectedVal=null) {
			if($options) {
				foreach($options as $value => $inner) {
					if($value == $selectedVal) { $selected = TRUE; } else { $selected = FALSE; }
					$return .= self::option($inner,array("value"=>$value),$selected);
				}
			}
			return($return);
		}

		public static function form_element($type,$attr=null,$data=array()) {
			switch($type) {
				case "input":
					if(!$attr['type']) { $attr['type'] = 'text'; }
					$return = self::openTag("input",$attr,TRUE);
				break;
				case "hidden":
					if(!$attr['type']) { $attr['type'] = 'hidden'; }
					$return = self::openTag("input",$attr,TRUE);
				break;
				case "textarea":
					$value = $attr['value'];
					unset($attr['value']);
					$return = self::openTag("textarea",$attr).$value.self::closeTag("textarea");
				break;
				case "password":
					$attr['type'] = "password";
					$return = self::form_element("input",$attr);
				break;
				case "submit":
					$attr['type'] = "submit";
					$return = self::form_element("input",$attr);
				break;
				case "select":

					if($data) {
						$options = NULL;
						foreach($data as $val) {
							if($attr['selected'] == $val['value']) { $selected = 'SELECTED'; } else { $selected = ''; }

							$options .= self::tag("option",$val['label'],array("value"=>$val['value']),$selected);
						}
					}

					$select = self::tag("select",$options,$attr);
					return($select);


				break;

				case "checkbox":
					$attr['type'] = 'checkbox';
					$array["true"] = "CHECKED";
					$return = self::openTag("input",$attr,TRUE,$array[$attr['value']]);
				break;
			}
			return($return);
		}

		public static function blindtext($length=200) {
			$text = "Er hörte leise Schritte hinter sich. Das bedeutete nichts Gutes. Wer würde ihm schon folgen, spät in der Nacht und dazu noch in dieser engen Gasse mitten im übel beleumundeten Hafenviertel? Gerade jetzt, wo er das Ding seines Lebens gedreht hatte und mit der Beute verschwinden wollte! Hatte einer seiner zahllosen Kollegen dieselbe Idee gehabt, ihn beobachtet und abgewartet, um ihn nun um die Früchte seiner Arbeit zu erleichtern?
				\n
			Oder gehörten die Schritte hinter ihm zu einem der unzähligen Gesetzeshüter dieser Stadt, und die stählerne Acht um seine Handgelenke würde gleich zuschnappen? Er konnte die Aufforderung stehen zu bleiben schon hören. Gehetzt sah er sich um. Plötzlich erblickte er den schmalen Durchgang. Blitzartig drehte er sich nach rechts und verschwand zwischen den beiden Gebäuden. Beinahe wäre er dabei über den umgestürzten Mülleimer gefallen, der mitten im Weg lag.
	\n
			Er versuchte, sich in der Dunkelheit seinen Weg zu ertasten und erstarrte: Anscheinend gab es keinen anderen Ausweg aus diesem kleinen Hof als den Durchgang, durch den er gekommen war. Die Schritte wurden lauter und lauter, er sah eine dunkle Gestalt um die Ecke biegen. Fieberhaft irrten seine Augen durch die nächtliche Dunkelheit und suchten einen Ausweg. War jetzt wirklich alles vorbei, waren alle Mühe und alle Vorbereitungen umsonst?
	\n
			Er presste sich ganz eng an die Wand hinter ihm und hoffte, der Verfolger würde ihn übersehen, als plötzlich neben ihm mit kaum wahrnehmbarem Quietschen eine Tür im nächtlichen Wind hin und her schwang. Könnte dieses der flehentlich herbeigesehnte Ausweg aus seinem Dilemma sein? Langsam bewegte er sich auf die offene Tür zu, immer dicht an die Mauer gepresst. Würde diese Tür seine Rettung werden? Er hörte leise Schritte hinter sich. Das bedeutete nichts Gutes.
	\n
			Wer würde ihm schon folgen, spät in der Nacht und dazu noch in dieser engen Gasse mitten im übel beleumundeten Hafenviertel? Gerade jetzt, wo er das Ding seines Lebens gedreht hatte und mit der Beute verschwinden wollte! Hatte einer seiner zahllosen Kollegen dieselbe Idee gehabt, ihn beobachtet und abgewartet, um ihn nun um die Früchte seiner Arbeit zu erleichtern? Oder gehörten die Schritte hinter ihm zu einem der unzähligen Gesetzeshüter dieser Stadt, und die stählerne Acht um seine Handgelenke würde gleich zuschnappen?
	\n
			Er konnte die Aufforderung stehen zu bleiben schon hören. Gehetzt sah er sich um. Plötzlich erblickte er den schmalen Durchgang. Blitzartig drehte er sich nach rechts und verschwand zwischen den beiden Gebäuden. Beinahe wäre er dabei über den umgestürzten Mülleimer gefallen, der mitten im Weg lag. Er versuchte, sich in der Dunkelheit seinen Weg zu ertasten und erstarrte: Anscheinend gab es keinen anderen Ausweg aus diesem kleinen Hof als den Durchgang, durch den er gekommen war. Die Schritte wurden lauter und lauter, er sah eine dunkle Gestalt um die Ecke biegen. Fieberhaft irrten seine Augen durch die nächtliche Dunkelheit und suchten einen Ausweg. War jetzt wirklich alles vorbei, waren alle Mühe und alle Vorbereitungen umsonst? Er presste sich ganz eng an die Wand hinter ihm und hoffte, der Verfolger würde ihn nicht finden.";

			$start = rand(0,400);

			if($length > strlen($text) + $start) {
				
				while($length > strlen($text)) {
					$text .= " ".$text;
				}
				
			}
			
			$text = str_replace(array("!","?"),".",$text);
			
			$text = substr($text,$start,$length);
			
			$start = stripos($text,".")+1;
			$text = substr($text,$start);
			$text = substr($text,0,strripos($text," "));

			return $text.".";
		}

		public static function data2html($data,$element,$attr=array()) {
			$return = NULL;
			if($data AND $element) {
				foreach($data as $line) {
					if($line) {
						unset($search);
						unset($replace);
						foreach($line as $key => $val) {
							$search[] = "/%".$key."%";
							$replace[] = $val;
						}
						$return .= str_replace($search,$replace,$element);
					}

				}
			}

			return(html::div($return,$attr));
		}

		public static function showMoreLink($button,$content) {
			$id = "id_".rand(0,9999999);
			$button = html::a($button,array("class"=>"showMoreLink","id"=>$id));

			$content = html::span($content,array("class"=>"showMoreContent","id"=>$id,"style"=>"display: none;"));

			$script = '<script type="text/javascript" charset="utf-8">
				$(document).ready(function() {
					$("a#'.$id.'.showMoreLink").live("click",function(e){
						e.preventDefault();
						$(this).remove();
						$("span#'.$id.'.showMoreContent").show();
					});
				});
			</script>';

			return($button.$content.$script);

		}

		public static function spalten($array=array()) {

			$anz = count($array);
			$width = round(100 / $anz,2);



			foreach($array as $key => $val) {
				$tdAttr[] = array("style"=>"width:".$width."%;");
				$lines[] = html::padding($val);
			}

			$table[] = $lines;
			$return = self::table($table,NULL,array("class"=>"spalten","style"=>"width:100%;"),$tdAttr);
			return($return);

		}





	public static function header($inner=null,$attr=array()) {
		return html::tag("header",$inner,$attr);
	}
	public static function footer($inner=null,$attr=array()) {
		return html::tag("footer",$inner,$attr);
	}
	public static function article($inner=null,$attr=array()) {
		return html::tag("article",$inner,$attr);
	}
	public static function aside($inner=null,$attr=array()) {
		return html::tag("aside",$inner,$attr);
	}
	public static function section($inner=null,$attr=array()) {
		return html::tag("section",$inner,$attr);
	}
	public static function nav($inner=null,$attr=array()) {
		return html::tag("nav",$inner,$attr);
	}
	public static function time($inner=null,$attr=array()) {
		return html::tag("time",$inner,$attr);
	}
	public static function address($inner=null,$attr=array()) {
		return html::tag("address",$inner,$attr);
	}






	public static function cols() {
		$cols 		= func_get_args();
		$colsCount 	= count($cols);
		$widthSum	= 0;
		
		$strings = array("one"=>1,"two"=>2,"three"=>3,"four"=>4,"five"=>5,"six"=>6);
		
		foreach($cols as $val) {
			if(is_array($val) AND is_numeric($val[1])) {
				$widthSum += $val[1];
				$diff += 1;
			}
			if(is_array($val) AND is_string($val[1])) {
				$p = explode("-",$val[1]);
				if(count($p) == 2) {
					$widthSum += $strings[$p[0]] / $strings[$p[1]]*100;
					$diff += 1;
				}
			}
		}
		
		if(($colsCount-$diff) != 0) {
			$stdColWidth	= round(((100-$widthSum)/($colsCount-$diff)),5);
		} else {
			$stdColWidth = 100;
		}
		
		foreach($cols as $key => $val) {
			$attr = array();
			
			if(is_array($val)) {
				$inner = $val[0];
				
				if(is_numeric($val[1])) {
					$attr['style'] .= "width:".$val[1]."%;";
				}
				if(is_string($val[1])) {
					$attr['class'] .= " ".$val[1];
				}
			} else {
				$inner = $val;
				$attr['style'] .= "width:".$stdColWidth."%;";
			}
			$attr['class'] .= " col";
			$rowInner .= html::div(html::padding($inner),$attr);
		}
		return html::div($rowInner,array("class"=>"row"));
	}





	

	public static function tabPages($obj,$attr=array()) {
		
		$attr['class'] .= " tabPages";
		
		if($obj) {
			foreach($obj as $id => $page) {
				if(is_array($page)) {
					if($id > 0) {$style = css::display("false"); $active=null;} else { $active = " active";}
					$menu 	.= html::li(html::padding($page['label']),array("id"=>$id,"class"=>$active));
					$pages 	.= html::div($page['content'],array("class"=>"page","id"=>$id,"style"=>$style));
				}
				
			}
		}
		
		$tabPages .= html::ul(html::padding($menu),array("class"=>"menu"));
		$tabPages .= html::div($pages,array("class"=>"pages"));
		$return = html::div(html::padding($tabPages),$attr);
		
		return($return);
		
		
	} 




	public static function loader($width="100%",$height="100%") {
		$loaderImg = html::img("../../sys/gfx/loader.gif");
		$top = str_replace("px","",$height) / 2;
		$left = str_replace("px","",$width) / 2;
		$inner = html::div($loaderImg,array("style"=>"position:relative;left:".$left."px;top:".$top."px;"));
		$return = html::div($inner,array("class"=>"loader","style"=>"position:relative;width:$width;height:$height;"));
		return($return);
	}

	public static function img($src,$attr=null) {
		$attr['src'] = $src;
		$return = self::openTag("img", $attr, TRUE);
		return($return);
	}

	public static function ajaxImg($url,$opt=null) {
		$id = md5(rand(0,9999999));

		$return = html::div(html::img("",array("class"=>"img")).html::loader($opt->width,$opt->height),array("id"=>$id,"style"=>"width:".$opt->width."px;height:".$opt->height."px;"));
		
		$return .= self::script('
			$(document).ready(function(){
				
				var img = $("#'.$id.'");
				var loader = img.find(".loader");
				var previewImage = img.find("img.img");
				
				previewImage.hide();
				previewImage.load(function(){
					loader.hide();
					previewImage.show();
				});
				
				previewImage.attr("src","'.$url.'");
				
			});
		');
		
		return($return);
	}

	public static function padding($inner,$margin=null) {
		if($margin) {
			$style = "margin:$margin;";
		} else {
			$style = NULL;
		}
		return(self::div($inner,array("class"=>"padding","style"=>$style)));
	}
	public static function relative($inner=null) {
		return(self::div($inner,array("class"=>"relative")));
	}
	
	public static function icq($nr) {
		$nr = str_replace(array(" ","-"), "", $nr);
		$return = self::span(
			self::img("http://status.icq.com/online.gif?icq=".$nr."&img=21", array("align"=>"absmiddle"))." ".
			$nr,
			array("class"=>"icq")
			);
		return($return);
	}
	public static function skype($name) {
		$return = self::span(
			self::img("http://mystatus.skype.com/smallicon/".$name, array("align"=>"absmiddle"))." ".
			$name,
			array("class"=>"skype")
			);
		return($return);
	}
	




	public static function menu($array,$attr=array()) {
		$attr['class'] .= " dropdown";
		
		if(is_array($array)) {
			foreach($array as $key => $val) {
				if(is_array($val)) {
					$first_key = key($val);
					if(is_array( $val[$first_key] )) {

						foreach($val[$first_key] as $subkey => $subval) {
							$sublis .= html::li(html::a(html::padding($subval[0]),$subval[1]));

						}
						$lis .= html::li(html::a(html::padding($first_key),$val[1]) . html::ul($sublis));
						unset($sublis);
					} else {
						$lis .= html::li(html::a(html::padding($val[0]),$val[1]));
					}
				}
			}
		}
		
		
		return html::ul($lis, $attr);
		
	}


	public static function code($inner,$attr=array()) {
		return html::pre($inner,$attr);
	}

}
?>