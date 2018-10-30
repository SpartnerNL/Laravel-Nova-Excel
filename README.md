<p align="center">
  <a href="https://laravel-excel.maatwebsite.nl">
    <img alt="Laravel Nova Excel" src="https://user-images.githubusercontent.com/7728097/43685313-ff1e2110-98b0-11e8-8b50-900a2b262f0f.png" />
  </a>
</p>

<h1 align="center">
  Laravel Nova Excel
</h1>

<h3 align="center">
  :muscle: :fire: :rocket:
</h3>

<p align="center">
  <strong>Supercharge your Laravel Nova resource exports</strong><br>
  
</p>

<p align="center">
  <a href="https://styleci.io/repos/146120416">
    <img src="https://styleci.io/repos/146120416/shield?branch=1.1" alt="StyleCI">
  </a> 
  
   <a href="https://packagist.org/packages/maatwebsite/laravel-nova-excel">
      <img src="https://poser.pugx.org/maatwebsite/laravel-nova-excel/v/stable.png" alt="Latest Stable Version">
  </a> 
  
  <a href="https://packagist.org/packages/maatwebsite/laravel-nova-excel">
      <img src="https://poser.pugx.org/maatwebsite/laravel-nova-excel/downloads.png" alt="Total Downloads">
  </a> 
  
  <a href="https://packagist.org/packages/maatwebsite/laravel-nova-excel">
    <img src="https://poser.pugx.org/maatwebsite/laravel-nova-excel/license.png" alt="License">
  </a>
</p>

<h4 align="center">
  <a href="https://laravel-excel.maatwebsite.nl/nova/1.1/getting-started/">Quickstart</a>
  <span> · </span>
  <a href="https://laravel-excel.maatwebsite.nl/nova/1.1/">Documentation</a>
  <span> · </span>
  <a href="https://laravel-excel.maatwebsite.nl/blog/">Blog</a>
  <span> · </span>
  <a href="https://laravel-excel.maatwebsite.nl/3.1/getting-started/contributing.html">Contributing</a>
  <span> · </span>
  <a href="https://laravel-excel.maatwebsite.nl/3.1/getting-started/support.html">Support</a>
</h4>

- **Easily export resources to Excel.** Supercharge your Nova resources and export them directly to an Excel or CSV document. Exporting has never been so easy.

- **Supercharged resource exports.** Export resources with automatic chunking for better performance. You provide us the query, we handle the performance. Exporting even larger resources? No worries, Laravel Nova Excel has your back. You can queue your exports so all of this happens in the background.

- **Export based on filters and selection.** Select or filter only certain resources and export only those to Excel!

- **Export lenses.** Got custom lenses defined? When exporting from a lens, it will use the query of the lens to determine which data needs to be exported!

## :rocket: 5 minutes quick start

:bulb: Require this package in the `composer.json` of your Laravel project. This will download the package and Laravel-Excel.

```
composer require maatwebsite/laravel-nova-excel
```

:muscle: Go to your resource. As example we'll use the `app/Nova/User.php`. Add `DownloadExcel` action to your `actions()` list.

```php
<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class User extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\User';
    
    // Other default resource methods
    
    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            new DownloadExcel,
        ];
    }
}
```

:fire: Go to your resource in your Nova admin panel, select all or some users and click "Download Excel"

![Laravel-Nova-Excel](https://user-images.githubusercontent.com/7728097/44807515-0dea4300-abca-11e8-9396-9bd969f6a6c9.png)

:page_facing_up: Find your `users.xlsx` in your downloads folder!

More installation instructions can be found at: [https://laravel-excel.maatwebsite.nl/nova/1.1/getting-started/installation.html](https://laravel-excel.maatwebsite.nl/nova/1.1/getting-started/installation.html)

## 🎓 Learning Laravel Nova Excel

You can find the full documentation of Laravel Nova Excel [on the website](https://laravel-excel.maatwebsite.nl/nova/1.1/).

We welcome suggestions for improving our docs. The documentation repository can be found at [https://github.com/Maatwebsite/laravel-excel-docs](https://github.com/Maatwebsite/laravel-excel-docs).

## :mailbox_with_mail: License & Postcardware

Our software is open source and licensed under the MIT license.

If you use the software in your production environment we would appreciate to receive a postcard of your hometown. Please send it to:

**Maatwebsite**  
Florijnruwe 111-2  
6218 CA Maastricht  
The Netherlands  

More about the license can be found at: [https://laravel-excel.maatwebsite.nl/3.1/getting-started/license.html](https://laravel-excel.maatwebsite.nl/3.1/getting-started/license.html)
