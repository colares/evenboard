<?php

class Paper extends PaperAppModel {

    public $name = 'Paper';
//    public $virtualFields = array(
//        'all_co_author' => 'CONCAT(Paper.co_author_name_1, ", ", Paper.co_author_name_2, ", ", Paper.co_author_name_3, ", ", Paper.co_author_name_4, ", ", Paper.co_author_name_5, ", ", Paper.co_author_name_6, ", ", Paper.co_author_name_7, ", ", Paper.co_author_name_8)'
//    );
    public $hasMany = array(
        'PaperResearchLine' => array(
            'className' => 'Paper.PaperResearchLine',
            'dependent' => true
        ),
        'Evaluation' => array(
            'className' => 'Paper.Evaluation',
            'dependent' => true
        )
    );
    public $belongsTo = array('Paper.PaperType', 'User');

    /**
     * Custom group by pagination and a calculated field
     * @see http://wiltonsoftware.com/posts/view/custom-group-by-pagination-and-a-calculated-field
     * @see http://mantis.apimenti.com.br/view.php?id=1394
     * @return void
     * @author 
     **/
    public function paginateCount($conditions = null, $recursive = 0, $extra = array()) {
        $parameters = compact('conditions');
        $this->recursive = $recursive;
        $count = $this->find('count', array_merge($parameters, $extra));
        if (isset($extra['group'])) {
            $count = $this->getAffectedRows();
        }
        return $count;
    }

    public function beforeSave($options = array()) {
        if (!empty($this->data['Paper']['submittedfile'])) {

            $file = $this->data['Paper']['submittedfile'];

            if (!empty($file['name'])) {
                $fp = fopen($file['tmp_name'], "rb");
                $content = fread($fp, $file['size']);
                $content = addslashes($content);
                fclose($fp);

                $this->data['Paper']['file'] = $content;
                $this->data['Paper']['mime_type'] = $file['type'];
            }
        }
        return true;
    }

    public $validate = array(
        'title' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'allowEmpty' => false,
                //'message' => 'You have not entered a title.'
                'message' => 'Você não digitou o título.'
            ),
	        'maxLengthNoBlankSpace' => array(
	            'rule' => array('maxLengthNoBlankSpace', 120),
	            'message' => 'O tamanho do título não pode ultrapassar o limite de 120 caracteres (sem espaço).'
	        )
        ),
       'abstract' => array(
           'notEmpty' => array(
               'rule' => 'notEmpty',
               // 'message' => 'You have not entered an abstract.'
               'message' => 'Você não digitou o resumo.'
           ),
	        'maxLengthNoBlankSpace' => array(
	            'rule' => array('maxLengthNoBlankSpace', 1492),
	            'message' => 'O tamanho do resumo não pode ultrapassar o limite de 1492 caracteres (sem espaço).'
	        )
       ),   
       'submittedfile' => array(
            'validateMime' => array(
                'rule' => 'validateMime',
                'allowEmpty' => true,
                // 'message' => 'This file format is not allowed.',
                'message' => 'Este tipo de arquivo não é permitido.',
            ),
            'notEmptyFile' => array(
                'on' => 'create',
                'rule' => 'notEmptyFile',
                // 'message' => 'You must upload a file.',
                'message' => 'Você precisa enviar um arquivo.',
                'allowEmpty' => true,
            ),
        ),
        'co_author_name_1' => array(
            'rule' => array('checkCompleteData', 1),
            // 'message' => 'Incomplete information. As you entered the Name, you must also inform the CPF and vice versa.',
            'message' => "O nome e o documento (CPF ou Passaporte) do(a) co-autor(a) são obrigatórios.",
        ),
        'co_author_name_2' => array(
            'rule' => array('checkCompleteData', 2),
            // 'message' => 'Incomplete information. As you entered the Name, you must also inform the CPF and vice versa.',
            'message' => "O nome e o documento (CPF ou Passaporte) do(a) co-autor(a) são obrigatórios.",
        ),
        'co_author_name_3' => array(
            'rule' => array('checkCompleteData', 3),
            // 'message' => 'Incomplete information. As you entered the Name, you must also inform the CPF and vice versa.',
            'message' => "O nome e o documento (CPF ou Passaporte) do(a) co-autor(a) são obrigatórios.",
        ),
        'co_author_name_4' => array(
            'rule' => array('checkCompleteData', 4),
            // 'message' => 'Incomplete information. As you entered the Name, you must also inform the CPF and vice versa.',
            'message' => "O nome e o documento (CPF ou Passaporte) do(a) co-autor(a) são obrigatórios.",
        ),
        'co_author_name_5' => array(
            'rule' => array('checkCompleteData', 5),
            // 'message' => 'Incomplete information. As you entered the Name, you must also inform the CPF and vice versa.',
            'message' => "O nome e o documento (CPF ou Passaporte) do(a) co-autor(a) são obrigatórios.",
        ),
        'co_author_name_6' => array(
            'rule' => array('checkCompleteData', 6),
            // 'message' => 'Incomplete information. As you entered the Name, you must also inform the CPF and vice versa.',
            'message' => "O nome e o documento (CPF ou Passaporte) do(a) co-autor(a) são obrigatórios.",
        ),
        'co_author_name_7' => array(
            'rule' => array('checkCompleteData', 7),
            // 'message' => 'Incomplete information. As you entered the Name, you must also inform the CPF and vice versa.',
            'message' => "O nome e o documento (CPF ou Passaporte) do(a) co-autor(a) são obrigatórios.",
        ),
        /**
         * CPF validator
         */
        // 'co_author_main_doc_1' => array(
        //     'numeric' => array(
        //         'rule' => 'numeric',
        //         'message' => 'Please supply your CPF only with numbers.',
        //         'allowEmpty' => true
        //     ),
        //     'minLength' => array(
        //         'rule' => array('minLength', 11),
        //         'message' => 'Inform the CPF with 11 digits.'
        //     ),
        //     'maxLength' => array(
        //         'rule' => array('maxLength', 11),
        //         'message' => 'Inform the CPF with 11 digits.'
        //     )
        // ),
        // 'co_author_main_doc_2' => array(
        //     'numeric' => array(
        //         'rule' => 'numeric',
        //         'message' => 'Please supply your CPF only with numbers.',
        //         'allowEmpty' => true
        //     ),
        //     'minLength' => array(
        //         'rule' => array('minLength', 11),
        //         'message' => 'Inform the CPF with 11 digits.'
        //     ),
        //     'maxLength' => array(
        //         'rule' => array('maxLength', 11),
        //         'message' => 'Inform the CPF with 11 digits.'
        //     )
        // ),
        // 'co_author_main_doc_3' => array(
        //     'numeric' => array(
        //         'rule' => 'numeric',
        //         'message' => 'Please supply your CPF only with numbers.',
        //         'allowEmpty' => true
        //     ),
        //     'minLength' => array(
        //         'rule' => array('minLength', 11),
        //         'message' => 'Inform the CPF with 11 digits.'
        //     ),
        //     'maxLength' => array(
        //         'rule' => array('maxLength', 11),
        //         'message' => 'Inform the CPF with 11 digits.'
        //     )
        // ),
        // 'co_author_main_doc_4' => array(
        //     'numeric' => array(
        //         'rule' => 'numeric',
        //         'message' => 'Please supply your CPF only with numbers.',
        //         'allowEmpty' => true
        //     ),
        //     'minLength' => array(
        //         'rule' => array('minLength', 11),
        //         'message' => 'Inform the CPF with 11 digits.'
        //     ),
        //     'maxLength' => array(
        //         'rule' => array('maxLength', 11),
        //         'message' => 'Inform the CPF with 11 digits.'
        //     )
        // ),
        // 'co_author_main_doc_5' => array(
        //     'numeric' => array(
        //         'rule' => 'numeric',
        //         'message' => 'Please supply your CPF only with numbers.',
        //         'allowEmpty' => true
        //     ),
        //     'minLength' => array(
        //         'rule' => array('minLength', 11),
        //         'message' => 'Inform the CPF with 11 digits.'
        //     ),
        //     'maxLength' => array(
        //         'rule' => array('maxLength', 11),
        //         'message' => 'Inform the CPF with 11 digits.'
        //     )
        // ),
        // 'co_author_main_doc_6' => array(
        //     'numeric' => array(
        //         'rule' => 'numeric',
        //         'message' => 'Please supply your CPF only with numbers.',
        //         'allowEmpty' => true
        //     ),
        //     'minLength' => array(
        //         'rule' => array('minLength', 11),
        //         'message' => 'Inform the CPF with 11 digits.'
        //     ),
        //     'maxLength' => array(
        //         'rule' => array('maxLength', 11),
        //         'message' => 'Inform the CPF with 11 digits.'
        //     )
        // ),
        // 'co_author_main_doc_7' => array(
        //     'numeric' => array(
        //         'rule' => 'numeric',
        //         'message' => 'Please supply your CPF only with numbers.',
        //         'allowEmpty' => true
        //     ),
        //     'minLength' => array(
        //         'rule' => array('minLength', 11),
        //         'message' => 'Inform the CPF with 11 digits.'
        //     ),
        //     'maxLength' => array(
        //         'rule' => array('maxLength', 11),
        //         'message' => 'Inform the CPF with 11 digits.'
        //     )
        // )
    );

    function validateMime() {
        if (!empty($this->data['Paper']['submittedfile']['size'])) {
            $file = $this->data['Paper']['submittedfile'];
            if (in_array($file['type'], Configure::read('PaperAllowedMimes')))
                return true;
            else {
                return false;
            }
        } else {
            return true;
        }
    }

    function notEmptyFile() {
        if (empty($this->data['Paper']['submittedfile']['size']))
            return false;
        else
            return true;
    }

    function maxLengthNoBlankSpace($check, $limit) {

		$checkNBS = str_replace(" ","",current($check));

        if (strlen($checkNBS) > $limit)
            return false;
        else
            return true;
    }

        /**
     * If co_author_name_N has been filled, its main doc is mandatory and vice-versa.
     *
     * @param string $check field name
     * @param string $index field index, set at validation array
     * @return void
     * @author Thiago Colares
     */
    function checkCompleteData($field, $index) {
        if (
            (
                empty($this->data['Paper']['co_author_name_' . $index])
                xor
                empty($this->data['Paper']['co_author_main_doc_' . $index])
            )
        ) {
            return false;
        } else {
            return true;
        }
    }
}
