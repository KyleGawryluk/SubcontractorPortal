<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
     Blade::directive('date', function ($date) {
      if ($date != null) {
         return "<?php echo date('m/d/Y',strtotime($date)); ?>";
      }
      return "";
   });

     Blade::directive('currency', function ($number) {
      if ($number != null) {
         return "<?php echo number_format(($number), 2)?>";
      }
      return "";
   });


     Blade::directive('emptyarr', function ($data) {
      if (is_array($data) && empty($data)) {
         return "EMPTY";
      }
      return $data;
   });



  }
}
