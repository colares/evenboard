<?php
/*
 * Add the Paper plugin 'locale' folder to your application locales' folders
 */
App::build(array('locales' => App::pluginPath('Paper') . DS . 'locale'));


/**
 * Deadlines
 */
/*
	TODO colocar no banco
/*/  
//                                                  HH  MM  SS  MM  DD  YYYY
Configure::write('PaperSubmissionLimitDate', mktime(23, 59, 59,  6,  30, 2012));
Configure::write('PaperEvaluationBeginDate', mktime(23, 59, 59,  6,  10, 2012));
Configure::write('PaperEvaluationLimitDate', mktime(23, 59, 59,  7,  20, 2012));


Configure::write('PaperSubmissionLimitPerUser', 2);


/**
 * Aux files
 *
 * @author Thiago Colares
 */
Configure::write('PaperSubmissionRules', array(
	array(
		'file' => array(
			'title' => __('Instruções para submissão e normas para confecção e apresentação de Tema Livre ou Poster'),
			'url' => '/paper/files/regras-de-submissao.doc'
		),
		'updated' => '31/05/2012'
	)
	
	//array('title' => __('Instructions for submition and standards for preparation and presentation of Open Theme or Poster'), 'url' => '/paper/files/regras-de-submissao.doc')
));

/*
	TODO colocar a extensão
*/
Configure::write('PaperAllowedMimes', array(
	'Word Office 2007 (.docx)'	=>	'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
	//'Word Office 2003 (.doc)'	=>	'application/msword' // .doc OpenOffice ou 
));
