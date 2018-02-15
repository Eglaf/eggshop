<?php

namespace App\Service\Content;

use App\Egf\Util;

/**
 * Class TextWithParams
 * It add parameter values to a text.
 * todo extendStringWithDynamicParameters getPlaceholdersFromString
 * todo Replace by array key to value... textWithVars->getExtended($text, ['%key1%' => 'val1', '{{ key2 }}' => 'val2'])
 * todo Use "{{ k }}" as "{{k}}".
 * todo Replace by index of placeholder keys... from back to forth direction?
 * todo Service... One Egf helper class, and an SF helper service calling that.
 * todo regexp?
 */
class TextWithParams {

	public function getExtendedByArray($text, $parameters) {
		$aPlaceholders = Util::getPlaceholdersFromString($text);
		$values       = [];
		// Iterate the dynamic parameters.
		foreach ($aPlaceholders as $key) {
			// Trim the string.
			$trimmedKey = trim($key, "{ }");
			// If the entity has a property like this then load the data.
			if (isset($parameters[$trimmedKey])) {
				$values[$key] = $parameters[$trimmedKey];
			}
			// Attribute wasn't found.
			else {
				throw new \Exception("Parameter array doesn't have the asked property! \n Identifier Code: {$trimmedKey} \n In text: {$key}");
			}
		}
		// Update the string with the data from the parameter array.
		foreach ($values as $key => $val) {
			$text = str_replace($key, $val, $text);
		}
		
		
		return $text;
	}
	
	/**
	 * @param $text
	 * @param $object
	 * @return mixed
	 */
	public function getExtendedByObject($text, $object) {
		return Util::extendStringWithDynamicParameters($text, $object);
	}

}