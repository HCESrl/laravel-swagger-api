<?php

namespace LaravelApi\Console;


use LaravelApi\ApiServer;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;


class ApiClearCommand extends Command
{
	
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'api:clear';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Remove the API cache file';
	
	/**
	 * The filesystem instance.
	 *
	 * @var \Illuminate\Filesystem\Filesystem
	 */
	protected $files;
	
	/**
	 * @var ApiServer
	 */
	protected $api;
	
	
	/**
	 * Create a new route clear command instance.
	 *
	 * @param  \Illuminate\Filesystem\Filesystem $files
	 * @return void
	 */
	public function __construct ( Filesystem $files, ApiServer $api )
	{
		parent::__construct ();
		
		$this->files = $files;
		
		$this->api = $api;
	}
	
	
	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function handle ()
	{
		$this->files->delete ( $this->api->getCachedApiPath () );
		
		$this->info ( 'API cache cleared!' );
	}
	
}
