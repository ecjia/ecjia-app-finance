<?php

namespace Ecjia\App\Finance;

use Royalcms\Component\App\AppServiceProvider;

class FinanceServiceProvider extends  AppServiceProvider
{
    
    public function boot()
    {
        $this->package('ecjia/app-finance');
    }
    
    public function register()
    {
        
    }
    
    
    
}