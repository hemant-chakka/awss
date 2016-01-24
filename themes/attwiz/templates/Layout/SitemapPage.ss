<% require themedCSS(SitemapPage) %>
<table class="contentpaneopen"><tbody><tr><td class="contentheading" width="100%">$Title</td></tr></tbody></table>
<div class="typography">
	$Content
	<% cached 'sitemap_page', ID, List(Page).Max(LastEdited) %>
		<% if Sitemap %>
			<div id="Sitemap">$Sitemap</div>
		<% end_if %>
	<% end_cached %>
	$Form
	$PageComments
</div>