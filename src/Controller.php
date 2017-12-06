<?php

namespace Finnegan\Settings;


use anlutro\LaravelSettings\SettingStore;
use Finnegan\Finnegan;
use Finnegan\Forms\ModelForm;
use Finnegan\Routing\ModelUrlGenerator;
use Finnegan\Routing\UrlGenerator;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as IlluminateController;


/**
 * @link https://github.com/anlutro/laravel-settings
 */
class Controller extends IlluminateController
{
	
	use ValidatesRequests;
	
	/**
	 * @var UrlGenerator
	 */
	protected $urls;
	
	/**
	 * @var SettingStore
	 */
	protected $settings;
	
	/**
	 * @var Setting
	 */
	protected $model;
	
	
	public function __construct ( Finnegan $app, Gate $gate, SettingStore $settings )
	{
		$this->settings = $settings;
		
		$this->urls = $app->make ( UrlGenerator::class );
		
		$this->model = $app->make ( 'settings.model' );
		$this->model->forceFill ( $this->settings->all () );
		$this->model->exists = true;
		
		view ()->share ( 'gate', $gate );
	}
	
	
	public function edit ( ModelForm $form, ModelUrlGenerator $urls )
	{
		$form->setModel ( $this->model )
			 ->url ( $this->urls->route ( 'store-settings' ) );
		
		$data = [
			'urls'  => $urls,
			'model' => $this->model,
			'form'  => $form,
			'icon'  => 'cog',
			'title' => trans ( 'finnegan::general.settings' )
		];
		
		return view ( 'finnegan::admin.crud.form.main', $data );
	}
	
	
	public function store ( Request $request )
	{
		$this->model->fillFromRequest ( $request );
		
		$this->settings->forgetAll ();
		
		$this->settings->set ( $this->model->getAttributes () );
		
		$this->settings->save ();
		
		return redirect ( $this->urls->route ( 'edit-settings' ) )
			->withMessage ( [ 'text' => trans ( "finnegan::messages.settings_updated" ), 'type' => 'success' ] );
	}
	
}