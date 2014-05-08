<?php
   //if this is a service call then dont echo the URL link
   $service = isset($_SESSION['serviceCall'])?$_SESSION['serviceCall']:false;

   if (extension_loaded('xhprof')) {
      $profiler_namespace = 'myapp';  // namespace for your application
      $xhprof_data = xhprof_disable();
      $xhprof_runs = new XHProfRuns_Default();
      $run_id = $xhprof_runs->save_run($xhprof_data, $profiler_namespace);

      if(!$service){
         // url to the XHProf UI libraries (change the host name and path)
         $profiler_url = sprintf('/xhprof/xhprof_html/index.php?run=%s&source=%s', $run_id, $profiler_namespace);
         echo '<a href="'. $profiler_url .'" target="_blank">Profiler output</a>';
      }
   }
