<?php header("Content-Type: text/html; charset=utf-8"); ?>
<!DOCTYPE html>
<html>
  <head>
    <title></title>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet"> 
    <!--<link rel="stylesheet" href="bootstrap.min.css">-->
    <script src="jquery.min.js"></script>
  </head>
  <body>
    <script src="d3.v3.min.js" charset="utf-8"></script>
    <script src="jquery-1.11.0.js"></script>
    <script type="text/javascript">
    
      // Prevent window close
      var hook = true;
      window.onbeforeunload = function() {
        if (hook) {       
          return "Are you sure that you want to end this survey? All of your answers will be lost.";
        }
      }
      function unhook() {
        hook=false;
      }
      
      var bodyWidth = $(document).width();
      var bodyHeight = $(document).height() - 20;
      if (bodyWidth < 800) bodyWidth = 800;
      if (bodyHeight < 750) bodyHeight = 750;
      var center = bodyWidth / 2;
      var middle = bodyHeight / 200;
      
      var textWidth = 800;
      var text_offset_top = 60;
      var title_offset_top = 70;
      var lineHeight = 18;
      var nodeLine = 360;
      
      var q_window_width = 100,
          q_window_height = 100,
          backdrop_width = 500;

      // left and top values for individual questions
      var question_lnum = center - (textWidth / 2);
      var string_l = question_lnum.toString();
      var string_t = "150px";
      var string_r_t = "45%",
          q_margin_top = 200,
          q_margin_top_str = q_margin_top.toString();

      // bar with boxes for answers
      var boxbar_margin = 10,
          boxbar_label_margin = 3,
          bar_target_height = 100,
          bar_target_width = ((bodyWidth - (boxbar_margin * 4) - 20) / 5),
          bar4_target_width = ((bodyWidth - (boxbar_margin * 3) - 20) / 4),
          bar5_target_width = ((bodyWidth - (boxbar_margin * 4) - 20) / 5),
          bar6_target_width = ((bodyWidth - (boxbar_margin * 5) - 20) / 6),
          bar_label_height = 25,
          boxbar_offset_x = 10,
          boxbar_offset_y = bodyHeight - bar_target_height - 100;

      var currSlide = 1;
      var numAlters = 0;
      var askedAbout = 0;
      var lastAskedAbout = 0;
      var numAsked = 1;
      var lastAnswered = 0;
      var numOther = 0;
      var checked = false;
      var altered = false;
      var skipped = false;
      var currNode = null;
      var nodeColor = '#9CD4D4',
          maleColor = '#a8a4ff',
          friendsColor = '#42f477',
          kidsColor = '#ffc1d8',
          kinderwensColor = '#ce88ae',
          kinderloosColor = '#c0d183',
          kinderhulpColor = '#c8b6db',
          kinderpraatColor = '#ef8f8f',
          answerColor = '#abff48';
          

      var startTime;
      var answers = [];
    </script>
    <script src="ie.js"></script>
    <script src="nodefunctions.js"></script>
    <script src="graph.js"></script>
    <script src="elementmanipulation.js"></script>    
    <script src="slides.js"></script>
    <script src="multiplechoiceelements.js"></script>
    <script src="shownext.js"></script>
    <script src="keypress.js"></script>
            
    <div class="input-group" id="name_input" method="get" onsubmit="addAlter()">
      <input type="text" id="alterName" class="form-control" placeholder="Naam" size="10">
      <button type="submit" id="alterSubmit" class="btn btn-default" position="inline" value="Enter" onclick="addAlter()">Voeg toe</button>
    </div>

    <div class="input-group" id="heeftPartner" method="get">
      <form id="heeftPrtnr">
        <span class="slideText">Hebt u op dit moment een partner?<br/>Met een partner bedoelen we iemand met wie u ten minste 3 maanden een relatie hebt.<br/>Ook echtgenoten tellen als partner.</span><br><br>
        <input type="radio" name="hP" value="ja"><span class="questionText">  Ja</span><br>
        <input type="radio" name="hP" value="nee"><span class="questionText">  Nee</span>
      </form>
    </div>

    <div class="input-group" id="zelfdePartner" method="get">
      <form id="zelfdePrtnr">
        <span class="slideText">Is dit dezelfde partner die u heeft genoemd in het laatste LISS-interview?</span><br><br>
        <input type="radio" name="zP" value="ja"><span class="questionText">  Ja</span><br>
        <input type="radio" name="zP" value="nee"><span class="questionText">  Nee</span><br>
        <input type="radio" name="zP" value="weetikniet"><span class="questionText">  Weet ik niet</span>
      </form>
    </div>
    
    <div class="input-group" id="geslachtPartner" method="get">
      <form id="geslachtPrtnr">
        <span class="slideText">Wat is het geslacht van uw partner?</span><br><br>
        <input type="radio" name="gP" value="man"><span class="questionText">  Man</span><br>
        <input type="radio" name="gP" value="vrouw"><span class="questionText">  Vrouw</span><br>
        <input type="radio" name="gP" value="anders"><span class="questionText">  Anders</span><br>
        <input type="radio" name="gP" value="zegiklieverniet"><span class="questionText">  Zeg ik liever niet</span>
      </form>
    </div>

    <div class="input-group" id="wonenPartner" method="get">
      <form id="wonenPrtnr">
        <span class="slideText">Woont u samen met uw partner?</span><br><br>
        <input type="radio" name="wP" value="ja"><span class="questionText">  Ja</span><br>
        <input type="radio" name="wP" value="nee"><span class="questionText">  Nee</span>
      </form>
    </div>

    <div class="input-group no-wrap" id="samenLeving" method="get">
      <form id="samenLvng">
        <span class="slideText">Wat voor samenlevingsvorm hebt u met uw partner:</span><br><br>
        <input type="radio" name="sL" value="huwelijk"><span class="questionText"> Huwelijk</span><br>
        <input type="radio" name="sL" value="regpartner"><span class="questionText"> Geregistreerd partnerschap</span><br>
        <input type="radio" name="sL" value="samencontract"><span class="questionText"> Samenlevingscontract</span><br>
        <input type="radio" name="sL" value="nietofficieel"><span class="questionText"> Geen officiële samenlevingsvorm</span>
      </form>
    </div>
    
    <div class="input-group no-wrap" id="geboortejaarPartner" method="get" onsubmit="return false;">
      <form id="geboortejaarPrtnr">
        <span class="slideText">Wat is uw partner’s geboortejaar?</span><br><br>
        <input type="text" name="gjP" placeholder="Vul een jaartal in.">
      </form>
    </div>

    <div class="input-group" id="hoelangRelatie" method="get">
      <form id="hoelangRlt">
        <span class="slideText">Hoe lang hebt u een relatie met uw partner?</span><br><br>
        <input type="radio" name="hlR" value="<1"><span class="questionText">  Korter dan 1 jaar</span><br>
        <input type="radio" name="hlR" value="1-2"><span class="questionText">  Tussen 1 en 2 jaar</span><br>
        <input type="radio" name="hlR" value="2-3"><span class="questionText">  Tussen 2 en 3 jaar</span><br>
        <input type="radio" name="hlR" value="3-4"><span class="questionText">   Tussen 3 en 4 jaar</span><br>
        <input type="radio" name="hlR" value="4-5"><span class="questionText">  Tussen 4 en 5 jaar</span><br>
        <input type="radio" name="hlR" value=">5"><span class="questionText"> Langer dan 5 jaar</span>
      </form>
    </div>

    <div class="input-group" id="heeftKinderen" method="get">
      <form id="heeftKndrn">
        <span class="slideText">Hebt u kinderen? Hiermee bedoelen we zowel biologische kinderen (gekregen met uw partner of met iemand anders) als stiefkinderen, adoptiekinderen en pleegkinderen.</span><br><br>
        <input type="radio" name="hK" value="ja"><span class="questionText">  Ja</span><br>
        <input type="radio" name="hK" value="nee"><span class="questionText">  Nee</span>
      </form>
    </div>

    <div class="input-group" id="hoeveelKinderen" method="get">
      <form id="hoeveelKndrn">
        <span class="slideText">Hoeveel kinderen hebt u? Hiermee bedoelen we zowel biologische kinderen (gekregen met uw partner of iemand anders) als stiefkinderen, adoptiekinderen en pleegkinderen.</span><br><br>
        <input type="radio" name="hvK" value="1"><span class="questionText">  1</span><br>
        <input type="radio" name="hvK" value="2"><span class="questionText">  2</span><br>
        <input type="radio" name="hvK" value="3"><span class="questionText">  3</span><br>
        <input type="radio" name="hvK" value="4"><span class="questionText">  4</span><br>
        <input type="radio" name="hvK" value="5"><span class="questionText">  5</span><br>
        <input type="radio" name="hvK" value="6"><span class="questionText">  6</span><br>
        <input type="radio" name="hvK" value="7"><span class="questionText">  7</span><br>
        <input type="radio" name="hvK" value="8"><span class="questionText">  8</span><br>
        <input type="radio" name="hvK" value="9"><span class="questionText">  9</span><br>
        <input type="radio" name="hvK" value="10"><span class="questionText">  10</span><br>
        <input type="radio" name="hvK" value=">10"><span class="questionText">  Meer dan 10</span>
      </form>
    </div>
	
    <div class="input-group" id="bioOuder" method="get">
      <form id="bioOdr">
        <span class="slideText">Van hoeveel van deze kinderen bent u de biologische ouder?</span><br><br>
        <input type="radio" name="bO" value="0" id="bO0"><span class="questionText">  0</span><br>
        <input type="radio" name="bO" value="1" id="bO1"><span class="questionText">  1</span><br>
        <input type="radio" name="bO" value="2" id="bO2"><span class="questionText">  2</span><br>
        <input type="radio" name="bO" value="3" id="bO3"><span class="questionText">  3</span><br>
        <input type="radio" name="bO" value="4" id="bO4"><span class="questionText">  4</span><br>
        <input type="radio" name="bO" value="5" id="bO5"><span class="questionText">  5</span><br>
        <input type="radio" name="bO" value="6" id="bO6"><span class="questionText">  6</span><br>
        <input type="radio" name="bO" value="7" id="bO7"><span class="questionText">  7</span><br>
        <input type="radio" name="bO" value="8" id="bO8"><span class="questionText">  8</span><br>
        <input type="radio" name="bO" value="9" id="bO9"><span class="questionText">  9</span><br>
        <input type="radio" name="bO" value="10" id="bO10"><span class="questionText">  10</span><br>
        <input type="radio" name="bO" value=">10" id="bO11"><span class="questionText">  Meer dan 10</span>
      </form>
    </div>

    <div class="input-group" id="stiefKinderen" method="get">
      <form id="stiefKndrn">
        <span class="slideText">Heeft UW PARTNER kinderen uit een vorige relatie?</span><br><br>
        <input type="radio" name="sK" value="ja"><span class="questionText">  Ja</span><br>
		<input type="radio" name="sK" value="nee"><span class="questionText">  Nee</span>
      </form>
    </div>
	
    <div class="input-group" id="wilKinderen" method="get">
      <form id="wilKndrn">
        <span class="slideText">Denkt u in de toekomst kinderen te krijgen?<br><br></span>
        <span class="slideTextAlt">Denkt u in de toekomst nog meer kinderen te krijgen?<br><br></span>
        <input type="radio" name="wK" value="absoluutniet"><span class="questionText">  Absoluut niet</span><br>
        <input type="radio" name="wK" value="waarschijnlijkniet"><span class="questionText">  Waarschijnlijk niet</span><br>
        <input type="radio" name="wK" value="weetikniet"><span class="questionText">  Weet ik niet</span><br>
        <input type="radio" name="wK" value="waarschijnlijkwel"><span class="questionText">   Waarschijnlijk wel</span><br>
        <input type="radio" name="wK" value="absoluutwel"><span class="questionText">  Absoluut wel</span>
      </form>
    </div>

    <div class="input-group" id="wilHoeveelKinderen" method="get">
      <form id="wilHvlKndrn">
        <span class="slideText">Hoeveel kinderen zou u graag willen, in totaal?<br><br></span>
        <span class="slideTextAlt1">Hoeveel kinderen zou u in totaal graag willen hebben? Dit is inclusief de <span class="hvkAnswer"></span> kinderen die u nu al hebt.<br><br></span>
        <span class="slideTextAlt2">Hoeveel kinderen zou u in totaal graag willen hebben? Dit is inclusief het kind dat u nu al hebt.<br><br></span>
        <input type="radio" name="whvK" value="0" id="whvK0"><span class="questionText">  0</span><br>
        <input type="radio" name="whvK" value="1" id="whvK1"><span class="questionText">  1</span><br>
        <input type="radio" name="whvK" value="2" id="whvK2"><span class="questionText">  2</span><br>
        <input type="radio" name="whvK" value="3" id="whvK3"><span class="questionText">  3</span><br>
        <input type="radio" name="whvK" value="4" id="whvK4"><span class="questionText">  4</span><br>
        <input type="radio" name="whvK" value="5" id="whvK5"><span class="questionText">  5</span><br>
        <input type="radio" name="whvK" value="6" id="whvK6"><span class="questionText">  6</span><br>
        <input type="radio" name="whvK" value="7" id="whvK7"><span class="questionText">  7</span><br>
        <input type="radio" name="whvK" value="8" id="whvK8"><span class="questionText">  8</span><br>
        <input type="radio" name="whvK" value="9" id="whvK9"><span class="questionText">  9</span><br>
        <input type="radio" name="whvK" value="10" id="whvK10"><span class="questionText">  10</span><br>
        <input type="radio" name="whvK" value="meer dan 10" id="whvK11"><span class="questionText">  Meer dan 10</span><br>
        <input type="radio" name="whvK" value="weetikniet" id="whvK12"><span class="questionText">  Weet ik niet</span>
      </form>
    </div>
    
    <div class="input-group" id="zekerHoeveelKinderen" method="get">
      <form id="zekerHvlKndrn">
        <span class="slideTextAlt1">U gaf op de vorige vraag aan dat u het liefste <span class="whvkAnswer"></span> kind(eren) zou willen.<br><br></span>
        <span class="slideTextAlt2">U gaf op de vorige vraag aan dat u het liefste <span class="whvmkAnswer"></span> meer kinderen zou willen dan u nu al heeft.<br><br></span>
        <span class="slideText">We zijn benieuwd naar hoe sterk uw voorkeuren zijn ten aanzien van het aantal kinderen dat u graag zou willen.</span><br><br>
        <input type="radio" name="zhvK" value="1"><span class="questionText">  Het maakt me niet veel uit of ik (meer) kinderen krijg, of niet</span><br>
        <input type="radio" name="zhvK" value="2"><span class="questionText">  Het aantal kinderen maakt me niet veel uit: een kind meer of minder is prima</span><br>
        <input type="radio" name="zhvK" value="3"><span class="questionText">  Ik wil heel graag (nogmaals) moeder worden, maar het aantal kinderen maakt me niet veel uit</span><br>
        <input type="radio" name="zhvK" value="4"><span class="questionText">  Minder kinderen zou ik prima vinden, maar liever niet meer</span><br>
        <input type="radio" name="zhvK" value="5"><span class="questionText">  Meer kinderen zou ik prima vinden, maar liever niet minder</span><br>
        <input type="radio" name="zhvK" value="6"><span class="questionText">  Ik wil liever niet meer of minder dan <span class="whvkAnswer"></span> kind(eren)</span><br>
        <input type="radio" name="zhvK" value="7"><span class="questionText">  Ik weet het nog niet zo goed</span>
      </form>
    </div>

    <div class="input-group" id="hoesnelKinderen" method="get">
      <form id="hoesnelKndrn">
        <span class="slideText">Binnen hoeveel jaar zou u het liefst uw eerste kind willen krijgen? Wanneer u op dit moment in verwachting bent, dan kunt u dit ook aangeven.<br><br></span>
        <span class="slideTextAlt">Binnen hoeveel jaar zou u het liefst uw kind willen krijgen? Wanneer u op dit moment in verwachting bent, dan kunt u dit ook aangeven.<br><br></span>
        <input type="radio" name="hsK" value="zwanger"><span class="questionText">  Ik ben op dit moment zwanger</span><br>
		    <input type="radio" name="hsK" value="zosnel"><span class="questionText">  Zo snel als het kan</span><br>
        <input type="radio" name="hsK" value="binnen2"><span class="questionText">  Binnen twee jaar</span><br>
        <input type="radio" name="hsK" value="tussen2en3"><span class="questionText">  Tussen twee en drie jaar vanaf nu</span><br>
        <input type="radio" name="hsK" value="tussen3en4"><span class="questionText">  Tussen drie en vier jaar vanaf nu</span><br>
        <input type="radio" name="hsK" value="tussen4en5"><span class="questionText">  Tussen vier en vijf jaar vanaf nu</span><br>
        <input type="radio" name="hsK" value="na5"><span class="questionText">  Over vijf jaar of later</span><br>
        <input type="radio" name="hsK" value="maaktnietuit"><span class="questionText">  Het maakt me niet zo veel uit wanneer ik kinderen zou krijgen</span><br>
        <input type="radio" name="hsK" value="weetniet"><span class="questionText">  Ik weet het nog niet zo goed</span>
      </form>
    </div>
    
    <div class="input-group" id="zekerHoesnelKinderen" method="get">
      <form id="zekerHsnlKndrn">
        <span class="slideTextAlt1">U gaf op de vorige vraag aan dat u het liefste <span class="zhsnlkAnswer"></span> uw eerste kind zou willen krijgen.<br><br></span>
        <span class="slideTextAlt2">U gaf op de vorige vraag aan dat u het liefste uw volgende kind <span class="zhsnlkAnswer"></span> zou willen krijgen.<br><br></span>
        <span class="slideText">We zijn benieuwd naar hoe sterk uw voorkeuren zijn ten aanzien van deze periode.</span><br><br>
        <input type="radio" name="zhsnlK" value="1"><span class="questionText">  Het maakt me niet veel uit wanneer ik kinderen krijg</span><br>
        <input type="radio" name="zhsnlK" value="2"><span class="questionText">  Het mag korter duren, maar liever niet langer</span><br>
        <input type="radio" name="zhsnlK" value="3"><span class="questionText">  Het mag langer duren, maar liever niet korter</span><br>
        <input type="radio" name="zhsnlK" value="4"><span class="questionText">  Ik weet het nog niet zo goed</span><br>
      </form>
    </div>
    
    <div class="input-group" id="geschiktAantalKinderen" method="get">
      <form id="geschiktAntlKndrn">
        <span class="slideText">Wat vindt u een geschikt aantal kinderen voor een gemiddelde familie in Nederland?</span><br><br>
        <input type="radio" name="gaK" value="0"><span class="questionText">  0</span><br>
        <input type="radio" name="gaK" value="1"><span class="questionText">  1</span><br>
        <input type="radio" name="gaK" value="2"><span class="questionText">  2</span><br>
        <input type="radio" name="gaK" value="3"><span class="questionText">  3</span><br>
        <input type="radio" name="gaK" value="4"><span class="questionText">  4</span><br>
        <input type="radio" name="gaK" value="5"><span class="questionText">  5</span><br>
        <input type="radio" name="gaK" value="6"><span class="questionText">  6</span><br>
        <input type="radio" name="gaK" value="7"><span class="questionText">  7</span><br>
        <input type="radio" name="gaK" value="8"><span class="questionText">  8</span><br>
        <input type="radio" name="gaK" value="9"><span class="questionText">  9</span><br>
        <input type="radio" name="gaK" value="10"><span class="questionText">  10</span><br>
        <input type="radio" name="gaK" value=">10"><span class="questionText">  Meer dan 10</span><br>
        <input type="radio" name="gaK" value="weetikniet"><span class="questionText">  Weet ik niet</span>
      </form>
    </div>
    
    <div class="input-group" id="gesprekPartner" method="get">
      <form id="gesprekPrtnr">
        <span class="slideText">Hebt u wel eens met uw partner gesproken over uw kinderwens?</span><br><br>
        <input type="radio" name="gkP" value="ja"><span class="questionText">  Ja</span><br>
        <input type="radio" name="gkP" value="nee"><span class="questionText">  Nee</span>
      </form>
    </div>

    <div class="input-group" id="verschilPartner" method="get">
      <form id="verschilPrtnr">
        <span class="slideText">Welke uitspraak omschrijft uw situatie het beste als het gaat om de kinderwens van u en uw partner?</span><br><br>
        <input type="radio" name="vP" value="allebeiniet"><span class="questionText">  We willen beiden <span id="nietmeer">niet meer </span><span id="geen">geen </span>kinderen</span><br>
        <input type="radio" name="vP" value="beidemeer"><span class="questionText">  We willen beiden graag <span id="meer">meer </span>kinderen, maar we hebben het gewenste aantal niet echt besproken</span><br>
        <input type="radio" name="vP" value="beidezelfde"><span class="questionText">  We willen beiden graag het zelfde aantal kinderen</span><br>
        <input type="radio" name="vP" value="iknietpartnerwel"><span class="questionText">  Ik wil <span id="nietmeer">niet meer </span><span id="geen">geen </span>kinderen, maar mijn partner wel</span><br>
        <input type="radio" name="vP" value="ikwelpartnerniet"><span class="questionText">  Ik wil graag <span id="meer">meer </span>kinderen, maar mijn partner niet</span><br>
        <input type="radio" name="vP" value="partnerminder"><span class="questionText">  Mijn partner wil minder kinderen dan ikzelf</span><br>
        <input type="radio" name="vP" value="partnermeer"><span class="questionText">  Mijn partner wil meer kinderen dan ikzelf</span><br>
        <input type="radio" name="vP" value="weetikniet"><span class="questionText">  Weet ik niet</span><br>
      </form>
    </div>
    
    <div class="input-group" id="leeftijdAlter" method="get">
      <form id="leeftijdAltr">
        <input type="radio" name="lA" value="18" id="lA18"><label for="lA18"><span class="questionText">  18</span></label><br>
        <input type="radio" name="lA" value="19" id="lA19"><label for="lA19"><span class="questionText">  19</span></label><br>
        <input type="radio" name="lA" value="20" id="lA20"><label for="lA20"><span class="questionText">  20</span></label><br>
        <input type="radio" name="lA" value="21" id="lA21"><label for="lA21"><span class="questionText">  21</span></label><br>
        <input type="radio" name="lA" value="22" id="lA22"><label for="lA22"><span class="questionText">  22</span></label><br>
        <input type="radio" name="lA" value="23" id="lA23"><label for="lA23"><span class="questionText">  23</span></label><br>
        <input type="radio" name="lA" value="24" id="lA24"><label for="lA24"><span class="questionText">  24</span></label><br>
        <input type="radio" name="lA" value="25" id="lA25"><label for="lA25"><span class="questionText">  25</span></label><br>
        <input type="radio" name="lA" value="26" id="lA26"><label for="lA26"><span class="questionText">  26</span></label><br>
        <input type="radio" name="lA" value="27" id="lA27"><label for="lA27"><span class="questionText">  27</span></label><br>
        <input type="radio" name="lA" value="28" id="lA28"><label for="lA28"><span class="questionText">  28</span></label><br>
        <input type="radio" name="lA" value="29" id="lA29"><label for="lA29"><span class="questionText">  29</span></label><br>
        <input type="radio" name="lA" value="30" id="lA30"><label for="lA30"><span class="questionText">  30</span></label><br>
        <input type="radio" name="lA" value="31" id="lA31"><label for="lA31"><span class="questionText">  31</span></label><br>
        <input type="radio" name="lA" value="32" id="lA32"><label for="lA32"><span class="questionText">  32</span></label><br>
        <input type="radio" name="lA" value="33" id="lA33"><label for="lA33"><span class="questionText">  33</span></label><br>
        <input type="radio" name="lA" value="34" id="lA34"><label for="lA34"><span class="questionText">  34</span></label><br>
        <input type="radio" name="lA" value="35" id="lA35"><label for="lA35"><span class="questionText">  35</span></label><br>
        <input type="radio" name="lA" value="36" id="lA36"><label for="lA36"><span class="questionText">  36</span></label><br>
        <input type="radio" name="lA" value="37" id="lA37"><label for="lA37"><span class="questionText">  37</span></label><br>
        <input type="radio" name="lA" value="38" id="lA38"><label for="lA38"><span class="questionText">  38</span></label><br>
        <input type="radio" name="lA" value="39" id="lA39"><label for="lA39"><span class="questionText">  39</span></label><br>
        <input type="radio" name="lA" value="40" id="lA40"><label for="lA40"><span class="questionText">  40</span></label><br>
        <input type="radio" name="lA" value="41" id="lA41"><label for="lA41"><span class="questionText">  41</span></label><br>
        <input type="radio" name="lA" value="42" id="lA42"><label for="lA42"><span class="questionText">  42</span></label><br>
        <input type="radio" name="lA" value="43" id="lA43"><label for="lA43"><span class="questionText">  43</span></label><br>
        <input type="radio" name="lA" value="44" id="lA44"><label for="lA44"><span class="questionText">  44</span></label><br>
        <input type="radio" name="lA" value="45" id="lA45"><label for="lA45"><span class="questionText">  45</span></label><br>
        <input type="radio" name="lA" value="46" id="lA46"><label for="lA46"><span class="questionText">  46</span></label><br>
        <input type="radio" name="lA" value="47" id="lA47"><label for="lA47"><span class="questionText">  47</span></label><br>
        <input type="radio" name="lA" value="48" id="lA48"><label for="lA48"><span class="questionText">  48</span></label><br>
        <input type="radio" name="lA" value="49" id="lA49"><label for="lA49"><span class="questionText">  49</span></label><br>
        <input type="radio" name="lA" value="50" id="lA50"><label for="lA50"><span class="questionText">  50</span></label><br>
        <input type="radio" name="lA" value="50+" id="lA50+"><label for="lA50+"><span class="questionText">  50+</span></label>
      </form>
    </div>
    
    <div class="input-group" id="relatieAlter" method="get" onsubmit="return false;">
      <form id="relatieAltr">
        <input type="checkbox" name="rA" value="1" id="rA1"><label for="rA1"><span class="questionText">  Dit is mijn partner</span></label><br>
        <input type="checkbox" name="rA" value="2" id="rA2"><label for="rA2"><span class="questionText">  Vader/Moeder</span></label><br>
        <input type="checkbox" name="rA" value="3" id="rA3"><label for="rA3"><span class="questionText">  Broer/Zus</span></label><br>
        <input type="checkbox" name="rA" value="4" id="rA4"><label for="rA4"><span class="questionText">  Ander familielid (bijvoorbeeld oom/tante, neef/nicht)</span></label><br>
        <input type="checkbox" name="rA" value="5" id="rA5"><label for="rA5"><span class="questionText">  Familielid van partner</span></label><br>
        <input type="checkbox" name="rA" value="6" id="rA6"><label for="rA6"><span class="questionText">  Kennis/vriend(in) van partner</span></label><br>
        <input type="checkbox" name="rA" value="7" id="rA7"><label for="rA7"><span class="questionText">  Van de basisschool</span></label><br>
        <input type="checkbox" name="rA" value="8" id="rA8"><label for="rA8"><span class="questionText">  Van de middelbare school</span></label><br>
        <input type="checkbox" name="rA" value="9" id="rA9"><label for="rA9"><span class="questionText">  Van studeren</span></label><br>
        <input type="checkbox" name="rA" value="10" id="rA10"><label for="rA10"><span class="questionText">  Via werk</span></label><br>
        <input type="checkbox" name="rA" value="11" id="rA11"><label for="rA11"><span class="questionText">  Via sociale activiteit (sport, hobby, kerk)</span></label><br>
        <input type="checkbox" name="rA" value="12" id="rA12"><label for="rA12"><span class="questionText">  Via een gezamenlijke kennis/vriend(in)</span></label><br>
        <input type="checkbox" name="rA" value="13" id="rA13"><label for="rA13"><span class="questionText">  Buurtgenoot</span></label><br>
        <input type="checkbox" id="rAcheckText" name="rA" value="14"><label for="rAcheckText"><span class="questionText">  Via een andere manier, namelijk:</span></label><br>
        <input type="text" id="rAtextInput" name="rA">
      </form>
    </div>
    
    <div class="input-group" id="kinderenAlter" method="get">
      <form id="kinderenAltr">
        <input type="radio" name="kA" value="1" id="kA1"><label for="kA1"><span class="questionText">  In verwachting van eerste kind</span></label><br>      
        <input type="radio" name="kA" value="2" id="kA2"><label for="kA2"><span class="questionText">  1</span></label><br>
        <input type="radio" name="kA" value="3" id="kA3"><label for="kA3"><span class="questionText">  2</span></label><br>
        <input type="radio" name="kA" value="4" id="kA4"><label for="kA4"><span class="questionText">  3</span></label><br>
        <input type="radio" name="kA" value="5" id="kA5"><label for="kA5"><span class="questionText">  4</span></label><br>
        <input type="radio" name="kA" value="6" id="kA6"><label for="kA6"><span class="questionText">  5</span></label><br>
        <input type="radio" name="kA" value="7" id="kA7"><label for="kA7"><span class="questionText">  Meer dan 5</span></label><br>
        <input type="radio" name="kA" value="8" id="kA8"><label for="kA8"><span class="questionText">  Weet ik niet</span></label>
      </form>
    </div>
    
    <div class="input-group" id="leeftijdKindAlter" method="get">
      <form id="leeftijdKndAltr">
        <input type="radio" name="lkA" value="1" id="lkA1"><label for="lkA1"><span class="questionText">  In verwachting van eerste kind</span></label><br>
        <input type="radio" name="lkA" value="2" id="lkA2"><label for="lkA2"><span class="questionText">  Tussen 0 en 6 maanden</span></label><br>
        <input type="radio" name="lkA" value="3" id="lkA3"><label for="lkA3"><span class="questionText">  Tussen 6 en 12 maanden</span></label><br>
        <input type="radio" name="lkA" value="4" id="lkA4"><label for="lkA4"><span class="questionText">  Tussen 1 en 2 jaar</span></label><br>
        <input type="radio" name="lkA" value="5" id="lkA5"><label for="lkA5"><span class="questionText">  Tussen 2 en 3 jaar</span></label><br>
        <input type="radio" name="lkA" value="6" id="lkA6"><label for="lkA6"><span class="questionText">  Tussen 3 en 4 jaar</span></label><br>
        <input type="radio" name="lkA" value="7" id="lkA7"><label for="lkA7"><span class="questionText">  Tussen 4 en 5 jaar</span></label><br>
        <input type="radio" name="lkA" value="8" id="lkA8"><label for="lkA8"><span class="questionText">  Ouder dan 5 jaar</span></label><br>
        <input type="radio" name="lkA" value="9" id="lkA9"><label for="lkA9"><span class="questionText">  Weet ik niet</span></label>
      </form>
    </div>    
    
    <div class="input-group" id="levensplezierAlter" method="get">
      <form id="levensplezierAltr">
        <input type="radio" name="lpA" value="1" id="lpA1"><label for="lpA1"><span class="questionText">  Het levensplezier van <span class="naamAlter"></span> is groter geworden na de geboorte van het kind/de kinderen</span></label><br>
        <input type="radio" name="lpA" value="2" id="lpA2"><label for="lpA2"><span class="questionText">  Het levensplezier van <span class="naamAlter"></span> is gelijk gebleven na de geboorte van het kind/de kinderen</span></label><br>
        <input type="radio" name="lpA" value="3" id="lpA4"><label for="lpA4"><span class="questionText">  Het levensplezier van <span class="naamAlter"></span> is minder geworden na de geboorte van het kind/de kinderen</span></label><br>
        <input type="radio" name="lpA" value="4" id="lpA3"><label for="lpA3"><span class="questionText">  Ik weet het niet</span></label><br>
        <input type="radio" name="lpA" value="5" id="lpA5"><label for="lpA5"><span class="questionText">  Het kind van <span class="naamAlter"></span> is nog niet geboren</span></label>
      </form>
    </div>    
      
    <div class="input-group" id="kinderenLevensgeluk" method="get">
      <form id="kinderenLvnsglk">
        <span class="slideText">Welke uitspraak omschrijft uw gevoel het best als het gaat om het hebben van kinderen en levensgeluk?</span><br><br>
        <input type="radio" name="kL" value="1"><span class="questionText">  Mensen zonder kinderen zijn veel gelukkiger dan mensen met kinderen.</span><br>
        <input type="radio" name="kL" value="2"><span class="questionText">  Mensen zonder kinderen zijn iets gelukkiger dan mensen met kinderen</span><br>
        <input type="radio" name="kL" value="3"><span class="questionText">  Mensen met en mensen zonder kinderen zijn even gelukkig</span><br>
        <input type="radio" name="kL" value="4"><span class="questionText">  Mensen met kinderen zijn iets gelukkiger dan mensen zonder kinderen</span><br>
        <input type="radio" name="kL" value="5"><span class="questionText">  Mensen met kinderen zijn veel gelukkiger dan mensen zonder kinderen</span><br>
        <input type="radio" name="kL" value="6"><span class="questionText">  Ik weet het niet</span>
      </form>
    </div>
    
    <div class="input-group" id="stellingenMeerKids" method="get">
      <span class="slideText">In welke mate bent u het eens met de volgende uitspraken:</span><br><br>
      <form id="stellingenVrndn">
        <span class="slideText">De meeste van mijn vrienden vinden dat ik <span id="meer">meer </span>kinderen zou moeten krijgen.</span><br><br>
        <input type="radio" name="sV" value="1"><span class="questionText">  Helemaal mee eens</span><br>
        <input type="radio" name="sV" value="2"><span class="questionText">  Mee eens</span><br>
        <input type="radio" name="sV" value="3"><span class="questionText">  Een beetje mee eens</span><br>
        <input type="radio" name="sV" value="4"><span class="questionText">  Niet mee eens/niet mee oneens</span><br>
        <input type="radio" name="sV" value="5"><span class="questionText">  Een beetje niet mee eens</span><br>
        <input type="radio" name="sV" value="6"><span class="questionText">  Niet mee eens</span><br>
        <input type="radio" name="sV" value="7"><span class="questionText">  Helemaal niet mee eens</span><br>
        <input type="radio" name="sV" value="8"><span class="questionText">  Weet ik niet</span>
      </form>
      <form id="stellingenOuders">
        <span class="slideText">Mijn ouders/verzorgers vinden dat ik <span id="meer">meer </span>kinderen zou moeten krijgen.</span><br><br>
        <input type="radio" name="sO" value="1"><span class="questionText">  Helemaal mee eens</span><br>
        <input type="radio" name="sO" value="2"><span class="questionText">  Mee eens</span><br>
        <input type="radio" name="sO" value="3"><span class="questionText">  Een beetje mee eens</span><br>
        <input type="radio" name="sO" value="4"><span class="questionText">  Niet mee eens/niet mee oneens</span><br>
        <input type="radio" name="sO" value="5"><span class="questionText">  Een beetje niet mee eens</span><br>
        <input type="radio" name="sO" value="6"><span class="questionText">  Niet mee eens</span><br>
        <input type="radio" name="sO" value="7"><span class="questionText">  Helemaal niet mee eens</span><br>
        <input type="radio" name="sO" value="8"><span class="questionText">  Weet ik niet</span><br>
        <input type="radio" name="sO" value="9"><span class="questionText">  Niet van toepassing</span>
      </form>
    </div>     
      
    <div class="input-group" id="kinderenMakenGeluk" method="get">
      <form id="kinderenMknGlk">
        <span class="slideText">U hebt eerder verschillende personen genoemd: als u de mensen vergelijkt die ongeveer dezelfde leeftijd hebben als elkaar, denkt u dan dat de mensen met of de mensen zonder kinderen gelukkiger zijn?</span><br><br>
        <input type="radio" name="kMG" value="1"><span class="questionText">  Ik denk dat de mensen met kinderen veel gelukkiger zijn dan de mensen zonder kinderen</span><br>
        <input type="radio" name="kMG" value="2"><span class="questionText">  Ik denk dat de mensen met kinderen iets gelukkiger zijn dan de mensen zonder kinderen</span><br>
        <input type="radio" name="kMG" value="3"><span class="questionText">  Ik denk dat de mensen met en de mensen zonder kinderen even gelukkig zijn</span><br>
        <input type="radio" name="kMG" value="4"><span class="questionText">  Ik denk dat de mensen zonder kinderen iets gelukkiger zijn dan de mensen met kinderen</span><br>
        <input type="radio" name="kMG" value="5"><span class="questionText">  Ik denk dat de mensen zonder kinderen veel gelukkiger zijn dan de mensen met kinderen</span><br>
        <input type="radio" name="kMG" value="5"><span class="questionText">  Ik weet het niet</span>
      </form>
    </div>
    
    <div class="input-group" id="bronnen" method="get" onsubmit="return false;">
      <form id="brnnn">
        <span class="slideText">Bij het benoemen van de 25 namen: komen alle namen uit “uw geheugen”, of hebt u gebruik gemaakt van een opgeslagen lijst met contacten (bijvoorbeeld via uw mobiele telefoon, uw email, of Facebook)?</span><br><br>
        <input type="radio" name="br" value="1"><span class="questionText">  Alle namen komen uit het geheugen, en ik heb niet gebruik gemaakt van een lijst met contacten.</span><br>
        <input type="radio" name="br" value="2"><span class="questionText">  Ik heb gebruik gemaakt van mijn mobiele telefoon</span><br>
        <input type="radio" name="br" value="3"><span class="questionText">  Ik heb gebruik gemaakt van mijn email</span><br>
        <input type="radio" name="br" value="4"><span class="questionText">  Ik heb gebruik gemaakt van Facebook</span><br>
        <input type="radio" id="brcheckText" name="br" value="5"><span class="questionText">  Ik heb gebruik gemaakt van iets anders, namelijk:</span>
        <input type="text" id="brtextInput" name="br">
      </form>
    </div>
    
    <div class="input-group" id="opmerkingen" method="get" onsubmit="return false;">
      <form id="opmrkngn">
        <span class="slideText">Hebt u nog opmerkingen over de vragenlijst? Als u per ongeluk iets verkeerd heeft ingevuld, kunt u dat hier ook melden.</span><br><br>
        <textarea rows="10" cols="50" id="opmtextArea" name="opm"></textarea>
      </form>
    </div>
    
    <div class="input-group" id="afsluiting" method="get">
      <form id="afsltng">
        <span class="slideText">Hartelijk dank voor het invullen van de vragenlijst.</span><br><br>
        <span class="slideText">Klikt u op de knop ‘LISS’ om terug te gaan naar uw persoonlijke pagina. VERGEET U DIT ALSTUBLIEFT NIET: pas dan worden uw antwoorden opgeslagen en kan uw vergoeding van €12,50 worden toegevoegd.</span><br><br>
        <span class="slideText">U kunt daar ook nog een opmerking over de vragenlijst maken. Ook als u per ongeluk iets verkeerd hebt ingevuld kunt u dat daar opmerkingen.</span><br><br>
      </form>
    </div>
    
    <div class="popop_box" id="nonresponse_box">
      <div class="popup_box" id="popup">
            <p class="popup_text">U hebt de vraag nog niet volledig beantwoord! Het zou fijn zijn voor het onderzoek als u de vraag volledig beantwoordt. Als u wel naar de volgende vraag wil gaan, dan kunt u weer op “Ga door” klikken.</p>
            <button class="btn btn-default" onclick="closePopup()">Sluiten</button>
      </div>
    </div>

    <div class="popop_box" id="onlyone_box">
      <div class="popup_box" id="onlyOnePopup">
            <p class="popup_text">Geef een naam op.</p>
            <button class="btn btn-default" onclick="closeOnlyOnePopup()">Sluiten</button>
      </div>
    </div>

    <div class="popop_box" id="fewAlters_box">
      <div class="popup_box" id="alterPopup">
            <p class="popup_text">U heeft nog geen 25 namen genoemd. Wij zouden het erg waarderen wanneer u precies 25 namen noemt. Als u moeite heeft om namen te noemen, dan kunt u misschien uw contactenboek raadplegen van uw telefoon, of uw email, of via Facebook of een soortgelijke website. Als u echt geen namen meer kan verzinnen, dan kunt u verder gaan met de vragenlijst.</p>
            <button class="btn btn-default" onclick="closeAlterPopup()">Sluiten</button>
      </div>
    </div>
    
    <div class="popop_box" id="reminderAlters_box">
      <div class="popup_box" id="reminderPopup">
            <p class="popup_text">Als u moeite heeft om namen te noemen, dan kunt u misschien uw contactenboek raadplegen van uw telefoon, of uw email, of via Facebook of een soortgelijke website.</p>
            <button class="btn btn-default" onclick="closeReminderPopup()">Sluiten</button>
      </div>
    </div>

    <div class="popop_box" id="fewDragged_box">
      <div class="popup_box" id="dragPopup">
            <p class="popup_text">You have not answered this question for every person in your network. It would very helpful for our research if you did. Please feel free to either give an answer or to go to the next question by clicking ‘Next’ again.”.</p>
            <button class="btn btn-default" onclick="closeDragPopup()">Sluiten</button>
      </div>
    </div>

    <div id="NextDiv">
      <input type="button" 
        class="btn btn-default" 
        value="Ga door"
        id="Next"
        onclick="showNext();pauseShowNext();" />
    </div>
    
    <div id="submitForm">
      <form method="POST" action="<?php echo $_POST['returnpage']; ?>">
        <input type="hidden" name="nomem" value="<?php echo $_POST['nomem']; ?>">
        <input type="hidden" name="sh" value="<?php echo $_POST['sh']; ?>">
        <input type="hidden" name="lsi" value="<?php echo $_POST['lsi']; ?>">
        <input type="hidden" name="pli" value="<?php echo $_POST['pli']; ?>">
        <input type="hidden" name="spi" value="<?php echo $_POST['spi']; ?>">
        <input type="hidden" name="aqi" value="<?php echo $_POST['aqi']; ?>">
        <input type="hidden" name="cqi" value="<?php echo $_POST['cqi']; ?>">
        <input type="hidden" name="<?php echo $_POST['varname1']; ?>" value=""> <!-- Value leeg laten. --> 
        <input type="hidden" name="<?php echo $_POST['statusvarname1']; ?>" value="<?php echo $_POST['statusvarvalue1']; ?>">

        <input type="submit" name="<?php echo $_POST['nextvarname']; ?>" value="LISS" class="btn btn-default" /><!-- Value kan ook Volgende zijn, net wat past in jouw vragenlijst. -->
      </form>
    </div>
    
    <script type="text/javascript">
        $("#Next").css("left",window.innerWidth * .8);
        $("#submitButton").css("left",window.innerWidth * .8);
    </script>
  </body>
</html>