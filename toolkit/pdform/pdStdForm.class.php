<?PHP

class pdStdForm {
	
	static public function login() {
		$form = pdform::create(array(
			pdform_stdElement::email("email",TRUE),
			pdform_stdElement::password("secret"),
			pdform_stdElement::submit("Login"),
		));

		return $form;
	}
	
	static public function register() {
		$form = pdform::create(array(
			array(
				"type"	=>	"text",
				"name"	=>	"firstname",
				"label"	=>	"Vorname",
				"placeholder"	=>	"Vorname",
				"validation"	=>	array(
									"required"	=>	array(TRUE,"Der Vorname fehlt."),
									"min_length"=>	array(2,"Bitte mindestens 2 Zeichen eingeben.")
				)
			),
			array(
				"type"	=>	"text",
				"name"	=>	"lastname",
				"label"	=>	"Nachname",
				"placeholder"	=>	"Nachname",
				"validation"	=>	array(
									"required"	=>	array(TRUE,"Der Nachname fehlt."),
									"min_length"=>	array(2,"Bitte mindestens 2 Zeichen eingeben.")
				)
			),
			pdform_stdElement::email("email",TRUE),
			pdform_stdElement::password("secret"),
			pdform_stdElement::submit("Registrieren"),
		));

		return $form;
	}
	
	static public function formElementCreator() {
		$form = pdform::create(array(
			array(
				"type"	=>	"select",
				"name"	=>	"type",
				"label"	=>	"Type",
				"placeholder" => "Bitte auswählen",
				"options"	=>	array(
					"text"		=>	"Text",
					"hidden"	=>	"Hidden",
					"password"	=>	"Password",
					"select"	=>	"Select",
					"radio"		=>	"Radio",
					"checkbox"	=>	"Checkbox",
				),
				"validation"	=>	array(
										"required"	=>	array(TRUE,"Type fehlt!"),
									)
			),
			array(
				"type"	=>	"text",
				"name"	=>	"name",
				"label"	=>	"Name",
				"placeholder"=>"Name",
				"validation"	=>	array(
										"required"	=>	array(TRUE,"Name fehlt!"),
										"min_length"=>	array(3,"Mindestens 3 Zeichen"),
									)
			),
			array(
				"type"	=>	"text",
				"name"	=>	"label",
				"label"	=>	"Label",
				"placeholder"=>"Label",
				"validation"	=>	array(
										"required"	=>	array(TRUE,"Label fehlt!"),
										"min_length"=>	array(3,"Mindestens 3 Zeichen"),
									)
			),
			array(
				"type"	=>	"text",
				"name"	=>	"placeholder",
				"label"	=>	"Placeholder",
				"placeholder"=>"Placeholder",
				"validation"	=>	array(
										"min_length"=>	array(3,"Mindestens 3 Zeichen"),
									)
			),
			
			pdform_stdElement::submit("Create")
		));
		return $form;
	}
	
}


class pdform_stdElement {
	
	static function email($name=null,$required=TRUE) {
		return array(
			"type"	=>	"text",
			"name"	=>	(isset($name)) ? $name : "email",
			"label"	=>	"eMail",
			"placeholder"	=>	"mail@example.com",
			"validation"	=>	array(
								"required"	=>	array($required,"Die eMail-Adresse fehlt."),
								"type"		=>	array("email","Das ist keine gültige eMail-Adresse",TRUE)
			)
		);
	}
	
	static function password($name=null) {
		return array(
			"type"	=>	"password",
			"name"	=>	(isset($name)) ? $name : "password",
			"label"	=>	"Passwort",
			"placeholder"	=>	"Passwort",
			"validation"	=>	array(
								"required"	=>	array(TRUE,"Das Passwort fehlt."),
								"min_length"=>	array(6,"Das Passwort muss mindestens 6 Zeichen haben.")
			)
		);
	}
	
	static function submit($label=null) {
		return array(
			"type"	=>	"submit",
			"label"	=>	$label,

		);
	}
	
}
?>