<?php
// --------------------------------------------------------------------------
namespace App\Providers;
// --------------------------------------------------------------------------
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
use Illuminate\Support\Facades\Auth;
use App\Models\CustomerNew;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class AppServiceProvider extends ServiceProvider {
    // ----------------------------------------------------------------------
    /**
     * Register any application services.
     * @return void
     */
    // ----------------------------------------------------------------------
    public function register(){
        require_once app_path() . '/Helpers/DatatablesHelper.php';
        require_once app_path() . '/Helpers/ImageHelper.php';
        require_once app_path() . '/Helpers/FileHelper.php';
        require_once app_path() . '/Helpers/Select2AjaxHelper.php';
        require_once app_path() . '/Helpers/AssetHelper.php';
        if ($this->app->environment() == 'local') {
            $this->app->register(\Reliese\Coders\CodersServiceProvider::class);
        }
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    /**
     * Bootstrap any application services.
     * @return void
     */
    // ----------------------------------------------------------------------
    public function boot(){
        // ------------------------------------------------------------------
        Schema::defaultStringLength(191);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        if( 'production' === env( 'APP_ENV' )) \URL::forceScheme('https'); // Enable HTTPS
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Route directive in Blade
        // ------------------------------------------------------------------
        Blade::directive( 'route', function( $arguments ){
            return "<?php echo route({$arguments}); ?>";
        });
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Include partial files with relative path
        // Based on https://stackoverflow.com/a/60020948
        // ------------------------------------------------------------------
        Blade::directive( 'relativeInclude', function( $args ){
            $args = Blade::stripParentheses( $args );
            // --------------------------------------------------------------
            $viewBasePath = Blade::getPath();
            foreach( $this->app['config']['view.paths'] as $path ){
                if( substr( $viewBasePath, 0, strlen( $path )) === $path ){
                    $viewBasePath = substr( $viewBasePath, strlen( $path ));
                    break;
                }
            }
            // --------------------------------------------------------------
            $viewBasePath = dirname( trim( $viewBasePath, '\/' ));
            $args = substr_replace( $args, $viewBasePath.'.', 1, 0 );
            return "<?php echo \$__env->make({$args}, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>";
            // --------------------------------------------------------------
        });
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Asset versioning directive
        // ------------------------------------------------------------------
        Blade::directive( 'assetv', function( $asset ){
            return "<?php echo assetv({$asset}); ?>";
        });
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // COUNT NEWS ON HEADER FRONTEND
        // ------------------------------------------------------------------
        view()->composer(
            ['*'], 
            function ($view) {
                $user = Auth::guard('user')->user();
                $news_count = 0;
                if($user != null){
                    $id = Auth::guard('user')->user()->id;
                    $news_count  = CustomerNew::where([['customers_id', $id], ['is_show', 0]])->get()->count();    
                }
                 
                $view->with('news_count', $news_count);
            }
        );
        
      
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------
}
