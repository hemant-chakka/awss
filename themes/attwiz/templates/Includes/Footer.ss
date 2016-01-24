<div class="moduletable">
	<div id="footerMenu2">
		<table style="width: 100%;" border="0">
			<tbody style="text-align: left;">
				<tr style="text-align: left;">
					<td style="text-align: left;" valign="top" width="25%">
						<% if $CurrentMember %>
							<p><a href="/manage-heatmaps/">Access Recent Heatmaps</a></p>
						<% else %>
							<p><strong>Company</strong></p>
							<p><a href="/about-us/">About Us</a></p>
							<p><a href="/about-us/media-mentions/">Media Praise</a></p>
							<p><a href="/about-us/affiliates/">Become an Affiliate</a></p>
							<p><a href="/contact-us/">Contact Us</a></p>
						<% end_if %>
					</td>
					<td style="text-align: left;" valign="top" width="30%">
						<% if $CurrentMember %>
							<p><a href="/account-settings/">Update your account information</a></p>
						<% else %>
							<p><strong>Using AttentionWizard</strong></p>
							<p><a href="/plans-and-pricing/">Subscription Plans and Pricing</a></p>
							<p><a href="/plans-and-pricing/prepaid-packages/">Non-expiring Heatmaps</a></p>
							<p><a href="/sign-up-now/">New User Registration</a></p>
							<p><a href="/customer-login/">Customer Login</a></p>
							<p><a href="/contact-us/">Customer Support</a></p>
						<% end_if %>
					</td>
					<td style="text-align: left;" valign="top" width="30%">
						<% if $CurrentMember %>
							<p><a href="/contact-us/">Contact Us</a></p>
						<% else %>
							<p><strong>About AttentionWizard</strong></p>
							<p><a href="/how-it-works/">How It Works</a></p>
							<p><a href="/how-it-works/results-and-accuracy/">Results and Accuracy</a></p>
							<p><a href="/how-it-works/heatmap-gallery/">Heatmap Gallery</a></p>
							<p><a href="/how-it-works/scientific-development/">The Science Behind AttentionWizard</a></p>
							<p><a href="/case-studies/">Case Studies</a></p>
							<p><a href="/how-it-works/faqs/">FAQs</a></p>
						<% end_if %>
					</td>
					<td style="text-align: left;" valign="top" width="15%">
						<% if $CurrentMember %>
							<p><a href="/sitemap/">Site Map</a></p>
						<% else %>
							<p><a href="/sitemap">Site Map</a></p>
							<p><a href="/privacy-policy/">Privacy Policy</a></p>
							<p><a href="/customer-login/terms-of-use/">Terms of Use</a></p>
						<% end_if %>
					</td>
				</tr>
				<tr style="text-align: center;">
					<td valign="top" colspan="4" style="text-align: center;">
						<p>Copyright &copy; $CopyrightYearRange. SiteTuners.com. All Rights Reserved.</p>
					</td>
				</tr>
			</tbody>
		</table>
	</div>		
</div>