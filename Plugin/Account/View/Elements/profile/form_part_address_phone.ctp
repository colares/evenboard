<fieldset>
    <legend><?php print __('Address and Phone Numbers'); ?></legend>
    <?php
    // --------------------- CEP ------------
	print $this->BForm->input('Profile.zipcode', array('label' => __('Zipcode'), 'maxlengh' => 10), array('tip' => __('Only numbers')));

    // --------------------- Address ------------
	print $this->BForm->input('Profile.address', array('label' => __('Address')), array('tip' => __('Street, Avenue, Square, etc')));
  	
	// --------------------- Address Number ------------
	print $this->BForm->input('Profile.address_number', array('label' => __('Number')));
	
	// --------------------- Address Complement ------------
	print $this->BForm->input('Profile.address_complement', array('label' => __('Complement')));
    
    $states = array("AC"=>"Acre", "AL"=>"Alagoas", "AM"=>"Amazonas", "AP"=>"Amapá","BA"=>"Bahia","CE"=>"Ceará","DF"=>"Distrito Federal","ES"=>"Espírito Santo","GO"=>"Goiás","MA"=>"Maranhão","MT"=>"Mato Grosso","MS"=>"Mato Grosso do Sul","MG"=>"Minas Gerais","PA"=>"Pará","PB"=>"Paraíba","PR"=>"Paraná","PE"=>"Pernambuco","PI"=>"Piauí","RJ"=>"Rio de Janeiro","RN"=>"Rio Grande do Norte","RO"=>"Rondônia","RS"=>"Rio Grande do Sul","RR"=>"Roraima","SC"=>"Santa Catarina","SE"=>"Sergipe","SP"=>"São Paulo","TO"=>"Tocantins");
    
	// --------------------- State ------------
	print $this->BForm->input('Profile.state', array('type' => 'select', 'label' => __('State'), 'empty' => false, 'options' => $states, 'class' => 'span3'));

	// --------------------- City ------------
	print $this->BForm->input('Profile.city', array('label' => __('City')));
    
	// --------------------- neighborhood ------------
	print $this->BForm->input('Profile.neighborhood', array('label' => __('Neighborhood')));
    
    $phone_types = array(
        array(
            'label' => __('Mobile'),
            'field' => 'Profile.mobile',
            'required' => true
        ),
        array(
            'label' => __('Residential Phone'),
            'field' => 'Profile.residential_phone'
        ),
        array(
            'label' => __('Business Phone'),
            'field' => 'Profile.business_phone'
        ),
        array(
            'label' => __('Fax'),
            'field' => 'Profile.fax'
        ),
    );
    
    foreach($phone_types as $item){
        $ddd = $this->Form->input($item['field'].'_ddd', array(
            'class' => "span1",
            'type' => 'text',
            'maxlength' => 2,
            'error' => false,
			'div' => false,
			'label' => false
        ));

        $phone = $this->Form->input($item['field'], array(
            'class' => "span2",
            'type' => 'text',
            'error' => false,
            'maxlength' => 8,
			'div' => false,
			'label' => false
        ));

        $divClass = 'clearfix';
        if(isset($item['required'])) $divClass .= ' required';
        
        $after = '';
        
        if ($this->Form->isFieldError($item['field'].'_ddd') || $this->Form->isFieldError($item['field'])){
            $divClass .= ' error';
            
            if($this->Form->isFieldError($item['field'].'_ddd'))
                $after .= $this->Form->error($item['field'].'_ddd');
            
            if($this->Form->isFieldError($item['field']))
                $after .= $this->Form->error($item['field']);
        }

        print $this->BForm->inputMix(
            $ddd . ' - ' . $phone , 
            array(
                'label' => $item['label'], 
                'required' => isset($item['required']),
				'error' => !empty($after),
				'errorMsg' => $after
            ), 
            array('tip' => __('DDD and Phone Number'))
        );
//        print $this->BForm->inputMix($ddd . ' - ' . $phone , array('label' => $item['label']), array('tip' => __('DDD and Phone Number')));
    }
    
    ?>
</fieldset>