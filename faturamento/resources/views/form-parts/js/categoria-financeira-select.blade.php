<script>
	$(document).ready(function() {
		  $('#dropdown-categoria-financeira-input').on('input', function() {
			  var searchText = $(this).val().toLowerCase();
			  var items = $('#dropdown-categoria-financeira-items .dropdown-item').toArray();
  
			  // Clear previous highlights and show all items
			  items.forEach(item => {
				  $(item).html($(item).text());
				  $(item).show();
			  });
  
			  // Filter and sort items based on the input
			  var matchedItems = items.filter(item => $(item).text().toLowerCase().includes(searchText));
			  var unmatchedItems = items.filter(item => !$(item).text().toLowerCase().includes(searchText));
  
			  // Move matched items to the top and highlight the matches
			  $('#dropdown-categoria-financeira-items').empty();
			  matchedItems.forEach(item => {
				  var highlightedText = $(item).text().replace(new RegExp(searchText, "gi"), (match) => `<span class="highlight">${match}</span>`);
				  $(item).html(highlightedText);
				  $('#dropdown-categoria-financeira-items').append(item);
			  });
			  unmatchedItems.forEach(item => {
				  $('#dropdown-categoria-financeira-items').append(item);
			  });
		  });
  
		  $(document).on('click', '.dropdown-categoria-financeira-item',function(e){
			  e.preventDefault();
			  let id = $(this).data('id');
			  let text = $(this).text();
			  console.log(id +' '+ text);
			  var truncatedText = text.length > 22 ? text.substring(0, 22) + '...' : text;
			  $('#dropdownCategoriaButton').text(truncatedText);
			  $('#categoriaFinanceiraVal').val(id);
		  });
	  });
  </script>
  