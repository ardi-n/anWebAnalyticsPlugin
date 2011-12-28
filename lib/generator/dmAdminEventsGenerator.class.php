<?php

/**
 * 
 * 
 * @author Adrian Nowicki <me@adrian-nowicki.com>
 *
 */
class dmAdminEventsGenerator extends dmAdminDoctrineGenerator {
	
	
 /**
	* Generates PHP files for a given module name.
	*
	* @param string $generatedModuleName The name of module name to generate
	* @param array  $files               A list of template files to generate
	*/
	protected function generatePhpFiles($generatedModuleName, $files = array())
	{
		
		foreach ($files as $file)
		{
			if (
					in_array($file, array('templates/_list_th_tabular.php', 'templates/_list_td_tabular.php'))
			) {
				
				foreach ($this->configuration->getValue('list.event_types') as $type) {
					
					$newFile = str_replace('.php', '', $file) . '_' . $type . '.php';
					
					$this->configuration->processedType = $type;
					
					/*
					 * compile one more time for each subtype of Events Module
					 */
					$this->configuration->compile();
					
					
					$this->getGeneratorManager()->save($generatedModuleName.'/'.$newFile, $this->evalTemplate($file));
				}
				
				$this->configuration->processedType = null;
				
			}
			
			/*
			* compile one more time for each subtype of Events Module
			*/
			$this->configuration->compile();
			
			$newFile = $file;
				
			$this->getGeneratorManager()->save($generatedModuleName.'/'.$newFile, $this->evalTemplate($file));
			
			
		}
	}
	
	
}