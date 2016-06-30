<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" >
<head>
		<% base_tag %>
		<link rel="icon" href="/favicon.ico" type="image/x-icon">
		<link href="/favicon.ico" rel="shortcut icon" type="image/x-icon" />
	  	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
  		<meta name="robots" content="index, follow" />
	  	<meta name="keywords" content="AttentionWizard, heatmaps," />
		<meta name="description" content="AttentionWizard.com Visual Attention Prediction Tool for Landing Pages" />
		<title><% if MetaTitle %>$MetaTitle<% else %>$TitleBarTitle<% end_if %></title>
  		<% require themedCSS(layout) %>
  		<% require themedCSS(style) %>
  		<% require themedCSS(highslide) %>
  		<% require themedCSS(highslide-sitestyles) %>
		<% require javascript(themes/attwiz/javascript/highslide-full.packed.js) %>
		<% require javascript(themes/attwiz/javascript/highslide-sitesettings.js) %>
		<% require javascript(themes/attwiz/javascript/homepage.js) %>
		<% require javascript(mysite/js/lost-password.js) %>
		<meta http-equiv="Content-Type" content="text/html; _ISO" />
		<meta http-equiv="Content-Style-Type" content="text/css" />
</head>
<body id="s5_body">
	<script type="text/javascript">
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-9266439-2']);
	  _gaq.push(['_trackPageview']);
	  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
   			(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(ga);
	  })();
	</script>
	<script type="text/javascript">
		var currentMemberID;
		<% if $CurrentMember %>
			currentMemberID = $CurrentMember.ID;
		<% else %>
			currentMemberID = 0;
		<% end_if %>
	</script>
	<div id="s5_outer_wrap">
		<div id="s5_top_wrap" style="width:985px">
			<div id="s5_tl_shadow"></div>
			<div id="s5_t_middle" style="width:985px">
				<div id="s5_tl_corner"></div>
				<div id="s5_t_repeat" style="width:985px"></div>
				<div id="s5_tr_corner"></div>
				<div style="clear:both"></div>
				<% include Header %>
				<div id="s5_main_body_outer" style="width:975px">
					<div id="s5_main_body_inner" style="width:975px">
						<!-- 
						<div id="s5_menu">
							<div id="s5_menu_tl"></div>
							<div id="s5_navv">
								<ul onmouseover="check_id()">
									<li class="active"><span class="s5_outer_active"><span class="s5_rs"><a class="active" href="/">Home</a></span></span></li>
									<li>
										<span class="s5_outer_active"><span class="s5_rs"><a class="active" href="/Overview/home.html">How it Works</a></span></span>		
										<ul>
											<li class='noback'><span><span class="s5_rs"><a class="sub" href="/Overview/results.html">Results</a></span></span></li>
											<li class='noback'><span><span class="s5_rs"><a class="sub" href="/Overview/gallery.html">Heatmap Gallery</a></span></span></li>
											<li class='noback'><span><span class="s5_rs"><a class="sub" href="/Overview/scientific-development.html">Scientific Development</a></span></span></li>
											<li class='noback'><span><span class="s5_rs"><a class="sub" href="/overview/faqs.html">FAQs</a></span></span></li>
										</ul>
									</li>
									<li>
										<span class="s5_outer_active"><span class="s5_rs"><a class="active" href="/plans-pricing/home.html">Plans & Pricing</a></span></span>		
										<ul>
											<li class='noback'><span><span class="s5_rs"><a class="sub" href="/Plans-Pricing/bronze.html">Bronze Level</a></span></span></li>
											<li class='noback'><span><span class="s5_rs"><a class="sub" href="/Plans-Pricing/silver.html">Silver Level</a></span></span></li>
											<li class='noback'><span><span class="s5_rs"><a class="sub" href="/Plans-Pricing/gold.html">Gold Level</a></span></span></li>
											<li class='noback'><span><span class="s5_rs"><a class="sub" href="/Plans-Pricing/prepaid-packages.html">Prepaid Packages</a></span></span></li>
										</ul>
									</li>
									<li>
										<span class="s5_outer_active"><span class="s5_rs"><a class="active" href="/case-studies/home.html">Case Studies</a></span></span>		
										<ul>
											<li class='noback'><span><span class="s5_rs"><a class="sub" href="/testimonials.html">Testimonials</a></span></span></li>
											<li class='noback'><span><span class="s5_rs"><a class="sub" href="/Case-Studies/credo-mobile.html">Credo Mobile</a></span></span></li>
											<li class='noback'><span><span class="s5_rs"><a class="sub" href="/Case-Studies/success-stories.html">Submit Your Case Study</a></span></span></li>
										</ul>
									</li>
									<li><span class="s5_outer_active"><span class="s5_rs"><a class="active" href="/register.html">Sign Up Now</a></span></span></li>
									<li>
										<span class="s5_outer_active"><span class="s5_rs"><a class="active" href="/about-us/home.html">About Us</a></span></span>		
										<ul>
											<li class='noback'><span><span class="s5_rs"><a class="sub" href="/About-Us/media-mentions.html">Media Mentions</a></span></span></li>
											<li class='noback'><span><span class="s5_rs"><a class="sub" href="/About-Us/affiliates.html">Affiliates</a></span></span></li>
										</ul>
									</li>
									<li>
										<span class="s5_outer_active"><span class="s5_rs"><a class="active" href="/login.html">Customer Login</a></span></span>		
										<ul>
											<li class='noback'><span><span class="s5_rs"><a class="sub" href="/Account/terms.html">Terms of Use</a></span></span></li>
										</ul>
									</li>
								</ul>
							</div>
							<div id="s5_menu_tr"></div>
							<div style="clear:both"></div>
						</div>
						-->
						
						<div id="s5_menu_bottom_wrap" style="height:25px">
							<div id="s5_menu_bottom_left" style="height:25px">
								<div id="s5_menu_bottom_right" style="height:25px; padding:0px"></div>
							</div>
						</div>
						<div class="s5_large_shadow"></div>
						<div id="s5_middle_wrapper">
							<div id="s5_left" style="width:200px">
								<div id="s5_left_inner">
									<div class="module_shadow_wrap">
										<div class="module_shadow"><div>
									<div>
								<div>
							</div>
						</div>
						
					</div>
				</div>
				<div class="s5_module_shadow_bottom"></div>
			</div>
			<div class="module_shadow_wrap">
				<div class="module_shadow">
					<div>
						<div>
							<div>
								<div class="module_menu">
									<div>
										<div>
											<div>
												<% include MenuLeft %>					
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="s5_module_shadow_bottom"></div>
			</div>
		</div>
	</div>
	<div id="s5_right" style="width:762px">
		<div id="s5_main_body_shadow">
			<% if isMainBodyFull %>
				<div id="s5_main_body_full" style="width:750px">
			<% else %>
				<div id="s5_main_body" style="width:600px">
			<% end_if %>
				<div id="s5_main_body2">
					<div id="s5_main_body3">
						<div id="s5_main_body4">
							$Message
							$Layout
							<span class="article_separator">&nbsp;</span>
						</div>	
					</div>	
				</div>	
			</div>	
		</div>
		<div id="s5_inset" style="width:150px">
			<div id="s5_inset_inner">
				<div class="module_shadow_wrap">
					<div class="module_shadow">
						<div>
							<div>
								<div>
									<% if $ContentRight %>
										<% if $URLSegment == "home" || $URLSegment == "plans-and-pricing" || $URLSegment == "about-us" %>
											<div id="rightHandColumn">
											<h2>Over $TotalUsersCount</h2>
										<% end_if %>
										<% if $URLSegment == "case-studies" || $URLSegment == "testimonials" || $URLSegment == "submit-your-case-study" %>
											<div id="rightHandColumn">
											<h4>Join the AttentionWizard Community</h4>
											<h2>Over $TotalUsersCount</h2>
										<% end_if %>
										$ContentRight
										<% if $URLSegment == "home" || $URLSegment == "plans-and-pricing" || $URLSegment == "case-studies" || $URLSegment == "testimonials" || $URLSegment == "submit-your-case-study" || $URLSegment == "about-us" %>
											</div>
										<% end_if %>
									<% end_if %>
								</div>
							</div>
						</div>
					</div>
					<div class="s5_module_shadow_bottom"></div>
				</div>
			</div>
		</div>
		<div id="s5_right_below_body2" style="width:369.5px">
			<div id="s5_right_below_body2_inner">
				<div class="module_shadow_wrap">
					<div class="module_shadow">
						<div>
							<div>
								<div>
									<div id="createdBySiteTuners"><a href="http://www.sitetuners.com" target="_blank"><img alt="Powered by SiteTuners" src="/themes/attwiz/images/SiteTuners-LogoPowered.png" height="30" width="105" /></a></div>		
								</div>
							</div>
						</div>
					</div>
					<div class="s5_module_shadow_bottom"></div>
				</div>
			</div>
		</div>	
	</div>
	<div style="clear:both"></div>
 </div>
</div>
</div>
</div>
<div id="s5_tr_shadow"></div>
<div style="clear:both"></div>
</div>
<div style="clear:both"></div>
<div id="s5_bottom_wrap" style="width:985px">
	<div id="s5_bl_corner"></div>
		<div id="s5_bot_gradient" style="width:985px">
			<div id="s5_footer_middle">
				<div id="s5_footer_left">
					<div id="s5_footer_right">
						<div id="s5_footer_text">
							<span class="footerc">
								Copyright &copy; $Now.Year.
								<a href="http://www.sitetuners.com" class="footerc" title="SiteTuners.com" target="_new">SiteTuners.com</a>. All Rights Reserved
							</span>
						</div>
						<div id="s5_bottom_pos"></div>
					</div>
				</div>
			</div>
			<div style="clear:both"></div>
			<div id="s5_footer_shadow"></div>
		</div>
		<div id="s5_br_corner"></div>
		<div style="clear:both"></div>
	</div>
	<div id="s5_bot_shadow" style="width:949px">
</div>
<% include Footer %>
</div>
<div style="height:17px"></div>
</body>
</html>