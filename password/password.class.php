<?PHP

class password {
	
	private static $staticsalt 	= "59d9a6df06b9f610f7db8e036896ed03662d168f4g6df8e1g6d54h68r4dfge68468df6d8f4e4f5g68e4f2g6e84f2ge468r4g2d34gr86d684dfg64j6s64k64df4t";
	private static $explorer 	= ":";
	
	
	public static function create($input) {
		if($input) {
			$uniquesalt = sha1($input.(microtime(1)*100).self::$staticsalt);
			$hash 		= self::hashIt($uniquesalt,$input);
			$return 	= $uniquesalt.self::$explorer.$hash;
		} else {
			$return = false;
		}
		
		
		return($return);
	}

	public static function validate($input,$hashinput) {
		
		if($input && $hashinput) {
			$hashinput 	= 	explode(self::$explorer,$hashinput);
			$uniquesalt	=	$hashinput[0];
			$hash 		= 	self::hashIt($uniquesalt,$input);

			if($hash == $hashinput[1]) {
				return(TRUE);
			} else {
				return(FALSE);
			}
		} else {
			return(FALSE);
		}
		
	}

	private function hashIt($uniquesalt,$input) {
		$hash 		= sha1(
						self::$staticsalt.
						$input.
						$uniquesalt.
						$input
						);
		return($hash);
	}
	
	public function securityLevel($pw) {
		$staerke = 1;
			// Prüfen ob Kleinuchstaben enthalten sind
		    if (preg_match( "/[a-z]+/", $pw )) {
		        // Stärkewert erhöhen
		        $staerke++;
		    }

		    // Prüfen ob Großbuchstaben enthalten sind
		    if (preg_match( "/[A-Z]+/", $pw )) {
		        // Stärkewert erhöhen
		        $staerke++;
		    }

		    // Prüfen ob Zahlen enthalten sind
		    if (preg_match( "/\d+/", $pw )) {
		        // Stärkewert erhöhen
		        $staerke++;
		    }

		    // Prüfen ob Sonderzeichen (!, ?, $, Leerzeichen, usw.) enthalten sind
		    if (preg_match( "/\W+/", $pw )) {
		        // Stärkewert erhöhen
		        $staerke++;
		    }

		    // Passwort Standardlänge (mindestens 6 Zeichen, maximal 15 Zeichen) prüfen
		    if (strlen( $pw ) >= 6 AND strlen( $pw ) <= 15) {
		        // Stärkewert erhöhen
		        $staerke++;
		    }
		    // Passwort relativ sichere Länge (mehr als 15 Zeichen) prüfen
		    elseif (strlen( $pw ) > 15) {
		        // Stärkewert um 2 erhöhen, da die Passwortlänge das wichtigste Sicherheitskriterium darstellt
		        $staerke = $staerke + 2;
		    }
		    // Zu kurzes Passwort wird auf niedrigsten Wert herabgestuft
		    elseif (strlen( $pw ) < 6) {
		        $staerke = 1;
		    }
		return($staerke);
	}
}

?>