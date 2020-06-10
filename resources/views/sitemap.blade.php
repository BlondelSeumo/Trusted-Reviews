<?php echo '<?xml version="1.0" encoding="UTF-8"?>' ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<url>
	<loc>{{ route( 'home' ) }}</loc>
</url>
<url>
	<loc>{{ route( 'categories' ) }}</loc>
</url>
<url>
	<loc>{{ route( 'addCompany' ) }}</loc>
</url>
<url>
	<loc>{{ route( 'companiesPlans' ) }}</loc>
</url>
<url>
	<loc>{{ route( 'tos' ) }}</loc>
</url>
<url>
	<loc>{{ route( 'privacy' ) }}</loc>
</url>
<url>
	<loc>{{ route( 'contact' ) }}</loc>
</url>
@foreach( $categories as $c )
<url>
	<loc>{{ route( 'browse-category', [ 'slug' => str_slug($c->name) ] ) }}</loc>
</url>
@endforeach
@foreach( $companies as $c )
<url>
	<loc>{{ route( 'reviewsForSite', [ 'site' => $c ] ) }}</loc>
</url>
@endforeach
</urlset>