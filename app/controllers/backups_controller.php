<?php 
class BackupsController extends AppController
{
	var $name = "Backups";
	var $helpers = array('Html', 'Form');
		
	function restore()
	{
		$this->helpers[] = "Time";
		$this->helpers[] = "Number";
		$this->pageTitle = "Restore";
		
		//$data = $this->paginate('Backup');
		//$this->set(compact('data'));
		
		$this->set('backups', $this->Backup->find('all', array('conditions' => array('user_id' => $this->Session->read('Auth.User.id')))));
	}
	
	/**
	 * Add file to backup
	 */
	 function add() 
	 {
	 	$this->pageTitle = "Backup";
		date_default_timezone_set("Europe/London");
		
		if (!empty($this->data))
		{
			foreach($this->data['Backup'] as $file)
			{
				if(is_uploaded_file($file['File']['tmp_name']))
				{
					$zip = zip_open($file['File']['tmp_name']);
					
					if(is_resource($zip))
					{
						while ($zip_entry = zip_read($zip))
						{
							if(zip_entry_filesize($zip_entry) <= 0) break;
							
							@mkdir("../../backups/{$this->Session->read('Auth.User.id')}");
							
							$this->Backup->create();
							
							// date isn't automagically inserted by Cake for some reason
							$this->data['Backup']['created'] = date( 'Y-m-d H:i:s');
							
							$this->data['Backup']['name'] = zip_entry_name($zip_entry);	
							$this->data['Backup']['size'] = zip_entry_filesize($zip_entry);
							$this->data['Backup']['data'] = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
							$this->data['Backup']['hash'] = md5($this->data['Backup']['data']);
							$this->data['Backup']['user_id'] = $this->Session->read('Auth.User.id');
							
							$fp = fopen("../../backups/{$this->Session->read('Auth.User.id')}/{$this->data['Backup']['name']}", 'wb');
							fwrite($fp, $this->data['Backup']['data']);
							fclose($fp);
							
							$this->Backup->save($this->data);
						}
					}
					else
					{
						if($file['File']['size'] <= 0) break;
						
						$this->Backup->create();
						
						// date isn't automagically inserted by Cake for some reason
						$this->data['Backup']['created'] = date( 'Y-m-d H:i:s');
						
						$this->data['Backup']['name'] = $file['File']['name'];
						$this->data['Backup']['size'] = $file['File']['size'];
						$this->data['Backup']['data'] = fread(fopen($file['File']['tmp_name'], "r"), $file['File']['size']);
						$this->data['Backup']['hash'] = md5($this->data['Backup']['data']);
						$this->data['Backup']['user_id'] = $this->Session->read('Auth.User.id');
						
						@mkdir("../../backups/{$this->Session->read('Auth.User.id')}");
						move_uploaded_file($file['File']['tmp_name'], "../../backups/{$this->Session->read('Auth.User.id')}/{$file['File']['name']}");
						
						$this->Backup->save($this->data);
					}
				}
			}
			
			$this->Session->setFlash('The selected files have been backed up.');
			$this->redirect('/backups/restore');
		}
    }

	function download($id) 
	{
		if($this->Backup->find('count', array('conditions' => array('Backup.id' => $id, 'Backup.user_id' => $this->Session->read('Auth.User.id')))) == 1)
		{
			Configure::write('debug', 0);
			$file = $this->Backup->findById($id);
			$fp = fopen("../../backups/{$this->Session->read('Auth.User.id')}/{$file['Backup']['name']}", 'r');
		
			header('Content-type: ' . $file['Backup']['type']);
			header('Content-length: ' . $file['Backup']['size']);
			header('Content-Disposition: attachment; filename="'.$file['Backup']['name'].'"');
			echo fread($fp, $file['Backup']['size']);
			exit();
		}
		else
		{
			$this->redirect('/backups/restore');
		}
	}
	
	function view($id)
	{
		if($this->Backup->find('count', array('conditions' => array('Backup.id' => $id, 'Backup.user_id' => $this->Session->read('Auth.User.id')))) == 1)
		{
			Configure::write('debug', 0);
			$file = $this->Backup->findById($id);
			$fp = fopen("../../backups/{$this->Session->read('Auth.User.id')}/{$file['Backup']['name']}", 'r');
		
			header('Content-type: ' . $file['Backup']['type']);
			header('Content-length: ' . $file['Backup']['size']);
			header('Content-Disposition: inline; filename="'.$file['Backup']['name'].'"');
			echo fread($fp, $file['Backup']['size']);
			exit();
		}
		else
		{
			$this->redirect('/backups/restore');
		}
	}
	
	function delete($id)
	{
		if($this->Backup->find('count', array('conditions' => array('Backup.id' => $id, 'Backup.user_id' => $this->Session->read('Auth.User.id')))) == 1)
		{
			$this->Backup->del($id);
			$this->Session->setFlash('The file has been deleted.');
		}
		
		$this->redirect('/backups/restore');
	}
}
?>