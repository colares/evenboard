<?php

//App::uses('FormHelper', 'View/Helper');

/**
 * Override some Form Helper stuffs to generate Bootstrap fom Twitter items
 *
 * @package default
 * @author Thiago Colares
 */
class BFormHelper extends FormHelper {
	
	var $helpers = array(
        'Html',
        'Layout',
        'Paginator'
    );


	/**
	 * Create an appropriate CREATE FORM based on Bootstrap from twitter
	 *
	 * @param string $model 
	 * @param string $formClass [ form-vertical | form-inline | form-search | form-horizontal ]
	 * @return string
	 * @author Thiago Colares
	 */
	public function create($model = null, $formClass = 'form-horizontal',$customOptions=array()){
		return parent::create($model, array_merge(array('class' => $formClass, 'inputDefaults' => array('label' => false, 'div' => false)),$customOptions)); 
	}


	/**
	 * Create an appropriate input for that field based on Bootstrap from twitter
	 *
	 * @param string $fieldName Slug field name
	 * @param string $options allows you to customize how input() works, and finely control what is generated.
	 * @param array $tip 'text' => Help text near by the input, 'type' => [ inline | horizontal ]
	 * @param string $formItem 
	 * @return void
	 * @author Thiago Colares
	 */
	// OUTPUT EXAMPLE!!
	// <div class="control-group">
	// 	<label class="control-label" for="input01">Text input</label>
	// 	<div class="controls">
	// 		<input type="text" class="input-xlarge" id="input01">
	// 		<p class="help-block">In addition to freeform text, any HTML5 text-based input appears like so.</p>
	// 	</div>
	// </div>
	public function input($fieldName, $options = array(), $tip = array()){
		$controlOptions = $options;
		$controlOptions['label'] = false;
        
		// http://api20.cakephp.org/view_source/helper#line-481
		$this->setEntity($fieldName);
		$fieldKey = $this->field();
        $modelKey = $this->model();

        if($this->_introspectModel($modelKey, 'validates', $fieldKey)){
            $options['label'] .= ' <span class="asterisk-required">*</span>';
        }
		
		$controls = $this->Html->div('controls',
			parent::input($fieldName, $controlOptions) .
			(($tip == null) ? '' : '<p class="help-' . (empty($tip['type']) ? 'block' : $tip['type']) . '">' . (is_array($tip) ? $tip['tip'] : $tip) . '</p>')
		);
		
		return $this->Html->div('control-group',
			parent::label($fieldName, (isset($options['label']) ? $options['label'] : false), array('class' => 'control-label')) . 
			$controls
		);
	}
	
	public function inputMix($inputMix = null, $options = array(), $tip = array()){
		$controlOptions = $options;
		$controlOptions['label'] = false;
        
        if(isset($options['required']) && $options['required'] == true) $options['label'] .= ' <span class="asterisk-required">*</span>';
		
		$controls = $this->Html->div('controls',
			$inputMix .
			(($tip == null) ? '' : '<p class="help-' . (empty($tip['type']) ? 'block' : $tip['type']) . '">' . (is_array($tip) ? $tip['tip'] : $tip) . '</p>')
		);

		$div = $this->Html->div('control-group',
			parent::label(null, (isset($options['label']) ? $options['label'] : false), array('class' => 'control-label')) . 
			$controls
		);
        
        // if($options['error']) {
        //     $div .= $options['errorMsg'];
        // }
        // 
        return $div;
		// 
		// $controlOptions = $options;
		// $controlOptions['label'] = false;
		// 
		// $controls = $this->Html->div('controls',
		// 	$inputMix .
		// 	(($tip == null) ? '' : '<p class="help-' . (empty($tip['type']) ? 'block' : $tip['type']) . '">' . (is_array($tip) ? $tip['tip'] : $tip) . '</p>')
		// );
		// 
		// return $this->Html->div('control-group',
		// 	$this->Form->label(null, (isset($options['label']) ? $options['label'] : false), array('class' => 'control-label')) . 
		// 	$controls
		// );




	}
	
	
	/**
	 * Create an uneditable input (without input), just label and text
	 * 
	 * @see "Uneditable input" at http://twitter.github.com/bootstrap/base-css.html#forms
	 * @param string $label Label from item
	 * @param string $info Info from item
	 * @param array $tip 'text' => Help text near by the input, 'type' => [ inline | horizontal ]
	 * @param string $options Options from item
	 * @return void
	 * @author Thiago Colares
	 */
	public function un($label, $info, $tip = array(), $options = array()){
		$controlOptions = $options;
		$controlOptions['label'] = false;
        
        $modelKey = $this->model();
		$fieldKey = $this->field();
        
        if($this->_introspectModel($modelKey, 'validates', $fieldKey) && isset($label)){
            $label .= ' <span class="asterisk-required">*</span>';
        }
		
		$controls = $this->Html->div('controls',
			'<span class="input-xlarge uneditable-input ' . (empty($options['class']) ? 'span9' : $options['class']) .'">' . $info . '</span>' . "\n" . 
			(($tip == null) ? '' : '<p class="help-' . (empty($tip['type']) ? 'block' : $tip['type']) . '">' . $tip['tip'] . '</p>')
		);
		
		return $this->Html->div('control-group',
			parent::label(null, $label, array('class' => 'control-label')) . 
			$controls
		);
	}
	
	
	/**
	 * Create an appropriate END FORM based on Bootstrap from twitter
	 *
	 * @param string $submitLabel Label for primary default submit button
	 * @param string $secondaryLabel Label for secondary default link button
	 * @param mixed $primary Set of primary submit / link items
	 * @param mixed $secondary Set of secondary submit / link items
	 * @return string Form ending html
	 * @author Thiago Colares
	 */
	public function end($primaryLabel = 'Save', $secondaryLabel = 'Cancel', $primary = null, $secondary = null){
		$items = array();
		
		if(empty($primary)){
			$primary[] = parent::submit(__($primaryLabel), array('div' => false, 'class' => 'btn btn-primary'));
		}
		
		$secondary = array();
		if(!empty($secondaryLabel) && empty($secondary)){
			$secondary[] = $this->Html->link(__($secondaryLabel), array(
	            'action' => 'index',
	        ), array(
	            'class' => 'btn',
	        ));
		}
		
		$items = array_merge($primary, $secondary);
		
		return $this->Html->div('form-actions', implode(' ', $items)) . parent::end();
	}

	public function requiredTip(){
		return '<p><span class="asterisk-required">*</span> ' . __('Marked fiels are mandatory.') . '</p>';
	}
	
}