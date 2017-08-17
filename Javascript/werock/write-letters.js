var array1 = [
		[12,-16,12],
		[12,-16,12],
		[12,-16,12],
		[12,-16,12],
		[12,-16,12],
		[12,-16,12],
		[12,-16,12],
		[12,-16,12],
		[12,-16,12],
		[12,-16,12],
		[12,-16,12],
		[12,-16,12],
		[12,-16,12],
		[12,-16,12],
		[12,-3,10,-3,12],
		[12,-3,10,-3,12],
		[12,-3,10,-3,12],
		[12,-3,10,-3,12],
		[12,-3,10,-3,12],
		[12,-3,10,-3,12],
		[40],
		[40],
		[40],
		[40],
		[40],
		[40],
		[40],
		[40]
		];
var array2 = [
		40,
		40,
		40,
		40,
		40,
		40,
		15,
		15,
		15,
		15,
		15,
		40,
		40,
		40,
		40,
		40,
		40,
		15,
		15,
		15,
		15,
		15,
		40,
		40,
		40,
		40,
		40,
		40
	];
	var array3 = [
		40,
		40,
		40,
		40,
		40,
		40,
		[15,-10,15],
		[15,-10,15],
		[15,-10,15],
		[15,-10,15],
		[15,-10,15],
		40,
		40,
		40,
		40,
		40,
		40,
		[30],
		[15,-1,15],
		[15,-2,15],
		[15,-3,15],
		[15,-4,15],
		[15,-5,15],
		[15,-6,15],
		[15,-7,15],
		[15,-8,15],
		[15,-9,15],
		[15,-10,15]
		

	];
	var array4 = [
		40,
		40,
		40,
		40,
		40,
		40,
		[15,-10,15],
		[15,-10,15],
		[15,-10,15],
		[15,-10,15],
		[15,-10,15],
		[15,-10,15],
		[15,-10,15],
		[15,-10,15],
		[15,-10,15],
		[15,-10,15],
		[15,-10,15],
		[15,-10,15],
		[15,-10,15],
		[15,-10,15],
		[15,-10,15],
		[15,-10,15],
		40,
		40,
		40,
		40,
		40,
		40



	];
	var array5 = [
		40,
		40,
		40,
		40,
		40,
		40,
		15,
		15,
		15,
		15,
		15,
		15,
		15,
		15,
		15,
		15,
		15,
		15,
		15,
		15,
		15,
		15,
		40,
		40,
		40,
		40,
		40,
		40

	];
	var array6 = [

		[15,-10,15],
		[15,-9,15],
		[15,-8,15],
		[15,-7,15],
		[15,-6,15],
		[15,-5,15],
		[15,-4,15],
		[15,-3,15],
		[15,-2,15],
		[15,-1,15],
		30,
		29,
		28,
		27,
		27,
		28,
		29,
		30,
		[15,-1,15],
		[15,-2,15],
		[15,-3,15],
		[15,-4,15],
		[15,-5,15],
		[15,-6,15],
		[15,-7,15],
		[15,-8,15],
		[15,-9,15],
		[15,-10,15]
	];

    var string = [
    {'id':'1','name':'First','text':'Dreams are successions of images, ideas, emotions, and sensations that occur involuntarily in the mind during certain stages of sleep.'},
    {'id':'2','name':'Second','text':'The content and purpose dreams are not definitively understood, though they have been a topic of scientific speculation, as well as a subject of philosophical and religious interest, throughout recorded history.'},
    {'id':'3','name':'Third','text':'The scientific study of dreams is called oneirology.'},
    {'id':'4','name':'Fourth','text':'Dreams mainly occur in the rapid-eye movement (REM) stage of sleep—when brain activity is high and resembles that of being awake.'},
    {'id':'5','name':'Fifth','text':'REM sleep is revealed by continuous movements of the eyes during sleep.'},
    {'id':'6','name':'Sixth','text':'At times, dreams may occur during other stages of sleep.'},
    {'id':'7','name':'Seventh','text':'However, these dreams tend to be much less vividor memorable.'}
    ]; 
    //var string = 'b';

    letter_mapping(string,array1,'W');
    letter_mapping(string,array2,'E');
    letter_mapping(string,array3,'R');
    letter_mapping(string,array4,'O');
    letter_mapping(string,array5,'C');
   	letter_mapping(string,array6,'K');
    function letter_mapping (string,letter_loc,id) 
    {
    	function isArray(o) 
    	{
  		return Object.prototype.toString.call(o) === '[object Array]';
		}
	    var current_letter_count = 0;
	    var string_count = 0;
	    var data_chunk=[];
		for (var i = 0; i < letter_loc.length; i++) 
		{
			if(letter_loc[i].length>0) 
			{
				for (var j = 0; j < letter_loc[i].length; j++) 
				{
						if (letter_loc[i][j]>0) 
						{
							for (var k = 0; k < letter_loc[i][j]; k++) 
							{
								if (current_letter_count==0) 
								{
									data_chunk += '\u003Cspan class="chunks" data-id="'+string[string_count].id+'" data-name="'+string[string_count].name+'" \u003E';
								}
								data_chunk += string[string_count].text[current_letter_count];
								current_letter_count++;
								
								if (string[string_count].text.length==current_letter_count) 
								{
									data_chunk += '</span>';
									string_count++;
									current_letter_count=0;
								}
								if(string.length==string_count)
								{
									string_count =0;
								}
							}
						}
						else
						{
							for (var k = letter_loc[i][j];k<0;k++) 
							{
								data_chunk += '&nbsp;';
							}
						}
				}
			}
			else 
			{
				for (var k = 0; k < letter_loc[i]; k++) 
							{
								if (current_letter_count==0) 
								{
									data_chunk += '\u003Cspan class="chunks" data-id="'+string[string_count].id+'" data-name="'+string[string_count].name+'" \u003E';
								}
								data_chunk += string[string_count].text[current_letter_count];
								current_letter_count++;
								
								if (string[string_count].text.length==current_letter_count) 
								{
									data_chunk += '</span>';
									string_count++;
									current_letter_count=0;
								}
								if(string.length==string_count)
								{
									string_count =0;
								}
							}
			}
			data_chunk += '<br />';
		}
		document.getElementById(id).innerHTML= data_chunk;
	}

	