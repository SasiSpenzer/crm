<?php

namespace App\Http\Controllers;

use App\City;
use App\article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
 	private $categories = ['sales', 'rentals', 'land','agents','homeloan','Appliances & Electronics', 'Bathrooms', 'Flooring', 'Furniture', 'Gardening', 'House Construction', 'Household items', 'Kitchen', 'Lighting', 'Other Services', 'Professionals', 'Service providers', 'Solar & Hot Water'];
	private $sales_sub_categories = ["House", "Apartment", "Commercial", "Bungalow", "Villa", "Studio"];
	private $rentals_sub_categories = ["House", "Apartment", "Commercial", "Bungalow", "Annexe", "Villa", "Studio", "Room"];
	private $lands_sub_categories = ["Bare Land", "Beachfront Land", "Cultivated Land", "Land with House", "Tea Land", "Rubber Land", "Coconut Land", "Paddy Land", "Cinnamon Land"];

    private $idealhome_sub_categories = ["A/C Repairs", "Air Conditioners", "Aluminum works", "Architects", "Arts and Crafts", "Bedding", "Beds and Mattresses", "Building Material", "Cabinets", "Carpenters", "Carpets and Rugs", "CCTV & Security", "CFL", "Chairs and Stools", "Chest of Drawers", "Civil Engineers", "Cleaning Services", "Coffee Tables", "Consultants", "Contractors", "Cookware", "Curtains and Blinds", "Dining Tables and Chairs", "Dinner Sets", "Doors", "Dressing Tables", "Electricians", "Fans", "Flash Lights", "Garden Lights", "Gates", "Glass", "Glassware", "Home Office", "Hot water system", "House Builders", "Interior Designers", "Internet and TV", "Kids and Baby", "Kitchen and Pantry Units", "Kitchen Appliances", "Landscape Artists", "Landscaping", "LED", "Machines for hire", "Masons", "Mirrors", "Moving Services", "Office furniture", "Outdoor and Balcony", "Paintings", "Paints", "Pantry cupboards", "Pest Control", "Pipes and Tanks", "Plumbers", "Pond construction", "Pots and Plants", "Power Tools", "Property Developers", "Quantity Surveyors", "Roof Repairs", "Roofing", "Showers", "Smart Home Devices", "Sofas", "Solar Lights", "Solar Panels", "Solicitors", "Sweage Cleaning", "Swimming Pool Construction", "Taps", "Terrazzo", "Tiles", "Turf", "TVs", "Valuers", "Wardrobes and Racks", "Water Leak Repairs", "Wiring and Plugs", "Wood"];

	private $all_sub_categories = ["House", "Apartment", "Commercial", "Bungalow", "Villa",
        "Studio", "Annexe", "Room", "Bare Land", "Beachfront Land", "Cultivated Land",
        "Land with House", "Tea Land", "Rubber Land",
        "Coconut Land", "Paddy Land", "Cinnamon Land","Lands","Sales","Rentals",'agents','homeloan',
        "Restaurant","Office","Hotel","Guest House","Factory","Warehouse","Shopping Mall","Shop",
        "Co-Working","Hospital","Multipurpose","Building", "A/C Repairs", "Air Conditioners", "Aluminum works", "Architects", "Arts and Crafts", "Bedding", "Beds and Mattresses", "Building Material", "Cabinets", "Carpenters", "Carpets and Rugs", "CCTV & Security", "CFL", "Chairs and Stools", "Chest of Drawers", "Civil Engineers", "Cleaning Services", "Coffee Tables", "Consultants", "Contractors", "Cookware", "Curtains and Blinds", "Dining Tables and Chairs", "Dinner Sets", "Doors", "Dressing Tables", "Electricians", "Fans", "Flash Lights", "Garden Lights", "Gates", "Glass", "Glassware", "Home Office", "Hot water system", "House Builders", "Interior Designers", "Internet and TV", "Kids and Baby", "Kitchen and Pantry Units", "Kitchen Appliances", "Landscape Artists", "Landscaping", "LED", "Machines for hire", "Masons", "Mirrors", "Moving Services", "Office furniture", "Outdoor and Balcony", "Paintings", "Paints", "Pantry cupboards", "Pest Control", "Pipes and Tanks", "Plumbers", "Pond construction", "Pots and Plants", "Power Tools", "Property Developers", "Quantity Surveyors", "Roof Repairs", "Roofing", "Showers", "Smart Home Devices", "Sofas", "Solar Lights", "Solar Panels", "Solicitors", "Sweage Cleaning", "Swimming Pool Construction", "Taps", "Terrazzo", "Tiles", "Turf", "TVs", "Valuers", "Wardrobes and Racks", "Water Leak Repairs", "Wiring and Plugs", "Wood"];

    public function index()
    {
    	$articles = Article::all();
 		return view('articles.index', compact('articles'));
    }

    public function create()
    {
        $cities = City::all('city_name');
    	$categories = $this->categories;
    	$sub_categories = $this->all_sub_categories;
        return view('articles.create', compact('sub_categories', 'cities', 'categories'));
    }

    public function store(Request $request)
    {
        if($request->sub_category == null)
            $sub_category = '';
        else
            $sub_category = $request->sub_category;

        if($request->city == null)
            $request->city = '';

        Article::create([
    		'city_name' => $request->city,
                'site' => $request->site,
    		'category' => $request->category,
    		'sub_category' => $sub_category,
                'main_title' => $request->main_title,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'is_active_meta_data' => $request->is_active_meta_data,
    		'article'	=> $request->article
    	]);

        $msg = "Successfully created!";

        return redirect('articles')->with('info', $msg);
    }

    public function show(article $article)
    {
        //
    }

    public function edit(article $article)
    {
    	$cities = City::all('city_name');
    	$categories = $this->categories;
    	$sub_categories = $this->all_sub_categories;
        return view('articles.edit', compact('article', 'sub_categories', 'cities', 'categories'));

        return redirect('articles');
    }

    public function update(Request $request, article $article)
    {
        if($request->sub_category == null)
            $sub_category = '';
        else
            $sub_category = $request->sub_category;

        if($request->city == null)
            $request->city = '';

        $article->update([
    		'city_name' => $request->city,
                'site' => $request->site,
    		'category' => $request->category,
    		'sub_category' => $sub_category,
                'main_title'   => $request->main_title,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'is_active_meta_data' => $request->is_active_meta_data,
    		'article'	=> $request->article
    	]);

        if($request->category == 'sales')
        	$category = 'sale';
        elseif ($request->category == 'land')
        	$category = 'land';
        elseif ($request->category == 'rentals')
        	$category = 'rentals';

        //$msg = "done!" . " <a href='https://www.lankapropertyweb.com/$category/index.php?searchbox=$request->city&location=&max=&search_area=&type=$request->sub_category&BR=&min=&posted_date=&search=1&srch_words='>testing url</a>";

        $msg = "Successfully updated!";

        return redirect('articles')->with('info', $msg);

    }

    public function destroy(article $article)
    {
        $article->delete();
        $msg = "Successfully deleted!";

        return redirect('articles')->with('info', $msg);
    }

    public function upload(Request $request)
    {
    	if ($_FILES['file']['name']) {
			if (!$_FILES['file']['error']) {
				$name = md5(rand(100, 200));
				$ext = explode('.', $_FILES['file']['name']);
				$filename = $name . '.' . $ext[1];
				$destination = '../../../images/articles/' . $filename;
				$location = $_FILES["file"]["tmp_name"];
				move_uploaded_file($location, $destination);
				echo 'https://www.lankapropertyweb.com/images/articles/' . $filename;
			}
			else
			{
			  echo $message = 'Ooops!  Your upload triggered the following error:  '.$_FILES['file']['error'];
			}
		}
    }
}
