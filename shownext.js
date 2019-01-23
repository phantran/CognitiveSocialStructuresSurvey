
// showNext(): Prepares for next slide in survey. Hides previous slide and shows currSlide,
// performing whatever operations needed for preparing slide.
// A bit like the main() function
// ---------------------------------------------------------------------------------------
//collected_data = {scio_question:{q_name:"", age: "", gender:"", native_language:"", major:"", experience:"", research_field:"", position:""}, 
//                  use_of_space:{day_in:"", time_slot:"", have_lunch:"", switch_desk:""}, area_usage:{zone1:"", zone2:"", zone3:"", zone4:""},
//                  relationships:{contacted_with:[]}};
function showNext() {
  if (currSlide === 1) {
    document.getElementById("slide0").style.display = "none";
    document.getElementById("slide1").style.display = "block";


    currSlide += 1;
  }else if (currSlide === 2){
    document.getElementById("slide1").style.display = "none";
    var ex2 = document.getElementById("personalInformation");
    ex2.style.left = string_l + "px";
    ex2.style.top = string_t;
    ex2.style.display = "block";

    let offset = $('#personalInformation').offset();
    let height = $('#personalInformation').height();
    let top = offset.top + height + 10 +  "px";
    $('#Next').css({
      'top': top
    });

    $('#Back').css({
      'top': top
    });


    currSlide += 1;
  }else if (currSlide === 3) {
      // If user has not selected an option, alert with popup
      if (($('#input_name').val() === '') && (checked === false)){
          promptNonresponse();
          checked = true;
      }else {
        checked = false;

         // Collect data before going on
        collected_data.socio_question.name = document.getElementById("input_name").value;
        collected_data.socio_question.age = document.getElementById("input_age").value;
        if(document.getElementById("gender_male").checked){
          collected_data.socio_question.gender = "male";
        }
        else if(document.getElementById("gender_female").checked){
          collected_data.socio_question.gender = "female";
        }
        else if(document.getElementById("other").checked){
          collected_data.socio_question.gender = "other";
        }
        else{
        }
        
        collected_data.socio_question.native_language = document.getElementById("select_language").value;
        collected_data.socio_question.major = document.getElementById("input_major").value;
        collected_data.socio_question.experience = document.getElementById("input_experience").value;
        collected_data.socio_question.research_field = document.getElementById("input_research_field").value;
        collected_data.socio_question.research_field = document.getElementById("input_position").value;

        //Display the new slide
        document.getElementById("personalInformation").style.display = "none";
        var ex3 = document.getElementById("UseOfSpace");
        ex3.style.left = string_l + "px";
        ex3.style.top = string_t;
        ex3.style.display = "block";

        let offset = $('#UseOfSpace').offset();
        let height = $('#UseOfSpace').height();
        let top = offset.top + height + 10 +  "px";
        $('#Next').css({
          'top': top
        });

        $('#Back').css({
          'top': top
        });
        currSlide += 1;
      }
  }
  else if (currSlide === 4) {
      // If user has not selected an option, alert with popup
      if ($('input[name=in_date]:checked').length === 0 && (checked === false)) {
          promptNonresponse();
          checked = true;
      } else {
        // Collect data before going on

        document.getElementById("UseOfSpace").style.display = "none";
        var ex4 = document.getElementById("UseOfSpace2");
        //ex4.style.left = string_l + "px";
        ex4.style.top = string_t;
        ex4.style.display = "block";

        $('.map1').maphilight({
          stroke: true,
          strokeColor: 'ff0000',
          strokeOpacity: 1,
          strokeWidth: 1,
        });

        $('map').imageMapResize();

        if(FloorA1Offset == 0 && FloorA1height == 0){
          FloorA1Offset = $('#A1Floor').offset();
          FloorA1height = $('#A1Floor').height();
        }
        let top = FloorA1Offset.top + FloorA1height + 20 +  "px";
        $('#Next').css({

          'top': top 
        });

        $('#Back').css({
          'top': top
        });


        currSlide += 1;
      }
  }
  else if (currSlide === 5) {
      // If user has not selected an option, alert with popup
    document.getElementById("UseOfSpace2").style.display = "none";
    var ex5 = document.getElementById("relationships");
    ex5.style.left = string_l + "px";
    ex5.style.top = string_t;
    ex5.style.display = "block";


    let offset = $('#relationships').offset();
    let height = $('#relationships').height();
    let top = offset.top + height + 10 +  "px";

    $('#Next').css({
      'top': top
    });

    $('#Back').css({
      'top': top
    });

    currSlide += 1;
  }
  else if (currSlide === 6) {
      // If user has not selected an option, alert with popup
    var name_array = [];
    var number_of_names = $("#addRelationships input").length;
    //console.log(number_of_names);
    for(i = 0; i < number_of_names; i++){
      name_input_id = 'input_contact' + i.toString();
      //console.log(document.getElementById("input_contact2").value);
      name_array.push(document.getElementById(name_input_id).value);
    }
    //console.log(name_array);
    document.getElementById("relationships").style.display = "none";
    //console.log(clicked_back);
    if(clicked_back === false){
      for(i = 0; i < name_array.length; i++){
        if(name_array[i] != ""){
          var table = document.getElementById("relationship_table1");
          var row = table.insertRow();
          var cell0 = row.insertCell(0);
          var cell1 = row.insertCell(1);
          var cell2 = row.insertCell(2);
          var cell3 = row.insertCell(3);
          var cell4 = row.insertCell(4);
          let temp = "howOften1" + i;
          let name = "name=" + temp;
          console.log(name);
        
          cell0.innerHTML = name_array[i];
          cell1.innerHTML = '<input type="radio"'+ name + ' value="never">';
          cell2.innerHTML = '<input type="radio"' + name + ' value="rarely">';
          cell3.innerHTML = '<input type="radio"' + name + ' value="sometimes">';
          cell4.innerHTML = '<input type="radio"' + name + ' value="often">';
        }
      }
  
      for(i = 0; i < name_array.length; i++){
        if(name_array[i] != ""){
          var table1 = document.getElementById("relationship_table2");
          var row1 = table1.insertRow();
          var cell10 = row1.insertCell(0);
          var cell11 = row1.insertCell(1);
          var cell12 = row1.insertCell(2);
          var cell13 = row1.insertCell(3);
          var cell14 = row1.insertCell(4);
          let name = "name=" + "howOften2" + i;
          cell10.innerHTML = name_array[i];
          cell11.innerHTML = '<input type="radio"'+ name + ' value="never">';
          cell12.innerHTML = '<input type="radio"' + name + ' value="rarely">';
          cell13.innerHTML = '<input type="radio"' + name + ' value="sometimes"';
          cell14.innerHTML = '<input type="radio"' + name + ' value="often">';
        }
      }
  
      var ex6 = document.getElementById("relationships2");
      ex6.style.left = string_l + "px";
      ex6.style.top = string_t;
      ex6.style.display = "block";
      currSlide += 1;
    }
    else{
      var count = document.getElementById("relationship_table1").rows.length;
      console.log(count);

      while(count > 1){
        let r = count;
        console.log(r - 1);
        document.getElementById("relationship_table1").deleteRow(r-1);
        document.getElementById("relationship_table2").deleteRow(r-1);
        count = count - 1;
      }
            // If user has not selected an option, alert with popup
      name_array = [];
      number_of_names = $("#addRelationships input").length;
      for(i = 0; i < number_of_names; i++){
        name_input_id = 'input_contact' + i.toString();
      //console.log(document.getElementById("input_contact2").value);
        name_array.push(document.getElementById(name_input_id).value);
      }

      document.getElementById("relationships").style.display = "none";

      for(i = 0; i < name_array.length; i++){
        if(name_array[i] != ""){
          let table = document.getElementById("relationship_table1");
          let row = table.insertRow();
          let cell0 = row.insertCell(0);
          let cell1 = row.insertCell(1);
          let cell2 = row.insertCell(2);
          let cell3 = row.insertCell(3);
          let cell4 = row.insertCell(4);
          let temp = "howOften1" + i;
          let name = "name=" + temp;
          console.log(name);
        
          cell0.innerHTML = name_array[i];
          cell1.innerHTML = '<input type="radio"'+ name + ' value="never">';
          cell2.innerHTML = '<input type="radio"' + name + ' value="rarely">';
          cell3.innerHTML = '<input type="radio"' + name + ' value="sometimes">';
          cell4.innerHTML = '<input type="radio"' + name + ' value="often">';
        }
      }
  
      for(i = 0; i < name_array.length; i++){
        if(name_array[i] != ""){
          let table1 = document.getElementById("relationship_table2");
          let row1 = table1.insertRow();
          let cell10 = row1.insertCell(0);
          let cell11 = row1.insertCell(1);
          let cell12 = row1.insertCell(2);
          let cell13 = row1.insertCell(3);
          let cell14 = row1.insertCell(4);
          let name = "name=" + "howOften2" + i;
          cell10.innerHTML = name_array[i];
          cell11.innerHTML = '<input type="radio"'+ name + ' value="never">';
          cell12.innerHTML = '<input type="radio"' + name + ' value="rarely">';
          cell13.innerHTML = '<input type="radio"' + name + ' value="sometimes"';
          cell14.innerHTML = '<input type="radio"' + name + ' value="often">';
        }
      }
  
      let ex6 = document.getElementById("relationships2");
      ex6.style.left = string_l + "px";
      ex6.style.top = string_t;
      ex6.style.display = "block";
      currSlide += 1;
    }
    let offset = $('#relationships2').offset();
    let height = $('#relationships2').height();
    let top = offset.top + height + 10 +  "px";
    $('#Next').css({
      'top': top
    });

    $('#Back').css({
      'top': top
    });
  }
  else if (currSlide === 7) {
      // If user has not selected an option, alert with popup
      if ($('input[name=in_date]:checked').length === 0 && (checked === false)) {
          promptNonresponse();
          checked = true;
      } else {
        document.getElementById("relationships").style.display = "none";
        document.getElementById("relationships2").style.display = "none";
        document.getElementById("network").style.display = "block";
        let offset = $('#chart').offset();
        let height = $('#chart').height();
        let top = offset.top + height + 10 +  "px";
        $('#Next').css({
          'top': top
        });
    
        $('#Back').css({
          'top': top
        });
      }
      currSlide += 1;
  }
  else if (currSlide === 8) {
      // If user has not selected an option, alert with popup
      if ($('input[name=in_date]:checked').length === 0 && (checked === false)) {
          promptNonresponse();
          checked = true;
      } else {
        document.getElementById("network").style.display = "none";
        document.getElementById("slide8").style.display = "block";
        let offset = $('#slide8').offset();
        let height = $('#slide8').height();
        let top = offset.top + height + 10 +  "px";
        $('#Next').css({
          'display': "none" 
        });
    
        $('#Back').css({
          'top': top
        });
        document.getElementById("finalSubmitButton").style.display = "block";
        $('#finalSubmitButton').css({
          'top': top,
          'left': "80%"
        });
      }
      currSlide += 1;
  }
  else if (currSlide === 9) {
      // If user has not selected an option, alert with popup
      if ($('input[name=in_date]:checked').length === 0 && (checked === false)) {
          promptNonresponse();
          checked = true;
      } else {
        document.getElementById("slide8").style.display = "none";
        document.getElementById("slide9").style.display = "block";
        $('#Next').css({
          'display': "none"
        });
    
        $('#Back').css({
          'display': "none"
        });

        $('#finalSubmitButton').css({
          'display': "none"
        });
      }
      currSlide += 1;
  }
  else{

  }
  $('#Next').blur();
  clicked_back = false;
}



function addMoreName() {
  event.preventDefault();
  contact_order = contact_order + 1;
  var objTo = document.getElementById('addRelationships');
  var divtest = document.createElement("div");
  divtest.innerHTML = '<input class="InputPeopleName" type="text" name="contact" id="input_contact'+ contact_order + '"'+ 'placeholder="Enter a name"> </span><br>';
  objTo.appendChild(divtest);
  $(function() {    
      var availableTags = ["Lebron James","Tran Phan","Tony Parker"]; 
      $( function() {    } );
      $( ".InputPeopleName" ).autocomplete({source: availableTags});  
  });
  let offset = $('#relationships').offset();
  let height = $('#relationships').height();
  let top = offset.top + height + 10 +  "px";
  $('#Next').css({
    'top': top
  });

  $('#Back').css({
    'top': top
  });
}



function goToFloorR1() {
  document.getElementById("floorA1").style.display = "none";
  let ex4 = document.getElementById("floorR1");
  ex4.style.top = string_t;
  ex4.style.display = "block";

  $('.map2').maphilight({
    stroke: true,
    strokeColor: 'ff0000',
    strokeOpacity: 1,
    strokeWidth: 1,
  });

  $('map').imageMapResize();


  let offset = $('#R1Floor').offset();
  let height = $('#R1Floor').height();
  let top = offset.top + height + 20 +  "px";

  $('#Next').css({
    'top': top 
  });

  $('#Back').css({
    'top': top
  });
}

function goToFloorR2() {
  document.getElementById("floorR1").style.display = "none";
  let ex4 = document.getElementById("floorR2");
  ex4.style.top = string_t;
  ex4.style.display = "block";

  $('.map3').maphilight({
    stroke: true,
    strokeColor: 'ff0000',
    strokeOpacity: 1,
    strokeWidth: 1,
  });

  $('map').imageMapResize();

  let offset = $('#R2Floor').offset();
  let height = $('#R2Floor').height();
  let top = offset.top + height + 20 +  "px";

  $('#Next').css({
    'top': top 
  });

  $('#Back').css({
    'top': top
  });
}

function dataSubmission() {
  showNext();
}




