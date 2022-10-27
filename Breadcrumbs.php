<?php

namespace App\Components;

use Illuminate\Support\Facades\Route;
use JetBrains\PhpStorm\NoReturn;

class Breadcrumbs extends Sitemap
{
    private array $segments;

     #[NoReturn] function __construct()
     {
         $this->segments=[];
     }

     private function get(string $segment_name): void
     {
         $index = array_search($segment_name,array_column( self::siteMapArray, 'route'));
         $this->segments[]=self::siteMapArray[$index];
         if (self::siteMapArray[$index]['parent'] !=''){
             $this->get(self::siteMapArray[$index]['parent']);
         }
     }
     public function Render($segment_name=null): void
     {
         if($segment_name==null)
             $segment_name = Route::current()->getName();

         $this->get($segment_name);
         $this->segments = array_reverse($this->segments);

         $html="<nav class='pt-lg-4 pb-3 mb-2 mb-sm-3' aria-label='breadcrumb'>";
         $html.="<ol class='breadcrumb mb-0'>";
         foreach ($this->segments as $index=>$segment)
         {
             if($index < count($this->segments)-1)
                 $html .="<li class='breadcrumb-item' aria-current='page'>".
                       "<a href='".$segment['route']."'>".
                       $segment['label'].
                       "</a></li>";
             else
                 $html .="<li class='breadcrumb-item' aria-current='page'>".$segment['label']."</li>" ;
         }
         $html.="</ol></nav>";
         echo ($html);
     }

}
