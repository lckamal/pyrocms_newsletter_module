<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is a Newsletter module for PyroCMS
 *
 * @author      Kamal Lamichhane
 * @website     http://lkamal.com.np
 * @package     PyroCMS
 * @subpackage  Newsletter
 */
class Newsletters extends Public_Controller
{
	function __construct()
	{
		parent::Public_Controller();
		$this->load->model('newsletter_m','newsletters');
		//$this->lang->load('newsletters');
	}
	
	function index()
	{
		$this->data->groups = $this->db->where('group_public',1)->get('newsletter_groups')->result();
		$this->template->build('index', $this->data);
	}
	
	function archive($id = '')
	{
		$this->data->newsletter = $this->newsletters_m->getNewsletter($id);
		if ($this->data->newsletter)
		{
			$this->template->build('view', $this->data);
		}
		else
		{
			show_404();
		}
	}
	
	// Public: Register for newsletter
	function subscribe()
	{
		$this->load->library('validation');
		$rules['email'] = 'trim|required|valid_email';
		$rules['name'] = 'trim|required';
		//$rules['group'] = 'trim|required';
		$this->validation->set_rules($rules);
		$this->validation->set_fields();
		
		if ($this->validation->run())
		{
			if ($this->newsletters->edit_recipient())
			{
				redirect('');
				return;
			}
			else
			{
				$this->session->set_flashdata(array('error'=> $this->lang->line('letter_add_mail_success')));
				redirect('');
				return;
			}
		}
		else
		{
			$this->session->set_flashdata(array('notice'=>$this->validation->error_string));
			redirect('');
			return;
		}
	}
	
	// Public: Register for newsletter
	function subscribed()
	{
		$this->template->build('subscribed', $this->data);
	}
	
	// Public: Unsubscribe from newsletter
	function unsubscribe($email = '')
	{
		if (!$email) redirect('');
		
		if ($this->newsletters_m->unsubscribe($email))
		{
			$this->session->set_flashdata(array('success'=> $this->lang->line('letter_delete_mail_success')));
			redirect('');
			return;
		}
		else
		{
			$this->session->set_flashdata(array('notice'=> $this->lang->line('letter_delete_mail_error')));
			redirect('');
			return;
		}
	}
	
}
?>