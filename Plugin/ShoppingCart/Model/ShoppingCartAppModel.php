<?php

class ShoppingCartAppModel extends AppModel {

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	protected function _mergeFindOptions($defaultOptions = array(), $extraOptions = array() ) {
		foreach ($extraOptions as $findOptionKey => $findOptionArray) {
			if (array_key_exists($findOptionKey, $defaultOptions)) {
				$defaultOptions[$findOptionKey] = array_merge($defaultOptions[$findOptionKey],$findOptionArray);
			} else {
				$defaultOptions[$findOptionKey] = $findOptionArray;
			}
		}
		return $defaultOptions;
	}

}

