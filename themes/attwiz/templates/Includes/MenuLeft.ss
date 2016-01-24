<ul class="menu" id="navMain">
	<% if CurrentMember %>
		<% loop Menu(1) %>	  
			<% if ShowInMenus && CustomerView %>
				<% if LinkOrSection == section %>
					<li id="$LinkingMode" class="parent active">
						<a  href="$Link" title="$Title.XML"><span>$MenuTitle.XML</span></a>
						<ul>
							<% loop Children %>
								<% if Last %>
									<li id="$LinkingMode" class="lastChild"><a href="$Link"  title="Go to the $Title.XML page" ><span>$MenuTitle.XML</span></a></li>
								<% else %>
									<li id="$LinkingMode"><a href="$Link"  title="Go to the $Title.XML page" ><span>$MenuTitle.XML</span></a></li>	
								<% end_if %>
							<% end_loop %>
						</ul>
	   			    </li>
				<% else %>
					<li id="$LinkingMode" class="parent"><a href="$Link"  title="$Title.XML"><span>$MenuTitle.XML</span></a></li>
				<% end_if %>
			<% end_if %>
		<% end_loop %>
	<% else %>
		<% loop Menu(1) %>	  
			<% if LinkOrSection == section %>
				<li id="$LinkingMode" class="parent active">
					<a  href="$Link" title="$Title.XML"><span>$MenuTitle.XML</span></a>
						<ul>
							<% loop Children %>
								<% if Last %>
									<li id="$LinkingMode" class="lastChild"><a href="$Link"  title="Go to the $Title.XML page" ><span>$MenuTitle.XML</span></a></li>
								<% else %>
									<li id="$LinkingMode"><a href="$Link"  title="Go to the $Title.XML page" ><span>$MenuTitle.XML</span></a></li>	
								<% end_if %>
							<% end_loop %>
						</ul>
				 </li>
			<% else %>
				<li id="$LinkingMode" class="parent"><a href="$Link"  title="$Title.XML"><span>$MenuTitle.XML</span></a></li>
			<% end_if %>
		<% end_loop %>
	<% end_if %>
</ul>