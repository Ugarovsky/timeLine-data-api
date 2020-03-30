<?php include './routing/service.php';?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="he-il" lang="he-il" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <script type="text/javascript" src="./index_files/jquery.min.js"></script>
    <script src="./js/windowPromise.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./index_files/jquery.custom-scrollbar.css" rel="stylesheet" type="text/css">
    <link href="./index_files/we.css" rel="stylesheet" type="text/css">
    <link href="./index_files/jquery.datepick.css" rel="stylesheet" type="text/css">
    <link href="./index_files/select2.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="./css/displayConfig.css">
    <link rel="stylesheet" href="./css/template.css">
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  
    <script src="https://kit.fontawesome.com/785b9f2688.js" crossorigin="anonymous"></script>
    <script src="./index_files/we.framework.js" type="text/javascript"></script>
    <script src="./index_files/jquery.custom-scrollbar.js" type="text/javascript"></script>
    <script src="./index_files/timeline.js" type="text/javascript"></script>
   
    <link href="./index_files/css" rel="stylesheet" type="text/css">
    <link href="./index_files/css(1)" rel="stylesheet">
    <link rel="stylesheet" href="./index_files/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/popup.css" >
    <link rel="stylesheet" href="./index_files/template.css" type="text/css">
</head>

<body dir="rtl" class="events assistant" id="body">

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width: auto; min-width: 0; max-width: 960px !important;">
    <div class="modal-content" id="popup-content" style="position:relative">
     
    </div>
  </div>
</div>


    <div class="main-timeline c-background main-timeline--standard c-text" data-role="timeline-view"
        style="opacity: 1;">
        <div class="main-background shaded" style="background-image: url(https://look.com.ua/pic/201210/1920x1080/look.com.ua-52763.jpg);">
        </div>
        <div class="timeline-wrapper " data-role="timeline-outer">
            <div class="timeline default-skin  scrollable active" data-role="timeline-inner" tabindex="-1">
                <div class="scroll-bar vertical">
                    <div class="thumb"></div>
                </div>
                <div class="viewport" style="width: 1366px;">
                    <div class="overview" id="overview" style="transform: translateX(-2469.24px); left: 0px;">
                    </div>
                </div>
                <div class="scroll-bar horizontal" style="width: 1366px; display: block;">
                    <div class="thumb" style="left: 144px; width: 112px;"></div>
                </div>
            </div>

            <div class="timeline-button timeline-button--right bg-background" data-event="requestScroll"
                data-event-data="end">
                <i class="fas fa-step-forward c-alt"></i>
            </div>

            <div class="timeline-button timeline-button--left bg-background" data-event="requestScroll"
                data-event-data="start">
                <i class="fas fa-step-backward c-alt"></i>
            </div>
            <div class="timeline-mobile-info mobile-only bg-alt c-background-light">
                <i class="fas fa-long-arrow-alt-right c-background-light"></i>
                <span class="info-text c-background-light">החלק ימינה ושמאלה</span>
                <i class="fas fa-long-arrow-alt-left c-background-light"></i>
            </div>
        </div>

    </div>

    <div class="templates" data-role="timeline-template" style="display:none;">
        <div class="" data-role="search-result-template-container">
            <a href="https://shamir.localtimeline.com/#" class="result-item c-text"
                data-role="search-result-item-wrapper">
                <span class="year" data-role="result-year">-</span>
                <span class="text" data-role="result-text">-</span>
            </a>
        </div>
    </div>





 


    <div class="we-message-window hidden-by-default we-modal--iframe" data-role="we-iframe-modal" data-is-modal=""
        data-name="iframe-modal">
        <div class="we-message__inner bg-background" data-role="modal-content-wrapper"
            style="max-width: 935px; height: 540px;">
            <div class="we-message__close" data-role="we-modal-close"></div>
            <div class="we-message_content bg-background" data-role="iframe-modal-content"></div>
        </div>
    </div>
    <div class="acsb-widget acsb-widget-position-right acsb-rtl acsb-widget-size-big acsb-ready" tabindex="-1"
        style="display: none;" aria-modal="true" aria-hidden="true" data-acsb="widget">

        <div class="acsb-main" data-acsb="main">

            <div class="acsb-header acsb-clearfix acsb-bg-lead">


                <a class="acsb-option-item acsb-option-close acsb-option-position-left" role="button"
                    data-acsb-option="close" href="https://shamir.localtimeline.com/#" aria-label="סגירת ממשק"
                    data-acsb-tooltip="סגירת ממשק">

                    <div class="acsb-make-center acsb-make-center-relative">
                        <i class="acsb-box-icon acsbi-close"></i>
                    </div>

                </a>


                <a class="acsb-option-item acsb-option-language acsb-option-position-left"
                    data-acsb-popup-trigger="acsb-language-popup" role="button" data-acsb-option="language"
                    href="https://shamir.localtimeline.com/#" aria-label="החלפת שפת ממשק"
                    data-acsb-tooltip="החלפת שפת ממשק">

                    <div class="acsb-make-center acsb-make-center-relative">
                        <span class="acsb-language-flag">
                            <img src="./index_files/he.svg" alt="עברית"> </span>
                        <span class="acsb-text-link">עברית</span>
                        <i class="acsb-box-icon acsbi-chevron_down"></i>
                    </div>

                </a>


                <a class="acsb-option-item acsb-option-help acsb-option-position-right"
                    data-acsb-popup-trigger="acsb-statement-popup" role="button" data-acsb-option="help"
                    href="https://shamir.localtimeline.com/#" aria-label="הצהרת נגישות"
                    data-acsb-tooltip="הצהרת נגישות">

                    <div class="acsb-make-center acsb-make-center-relative">
                        <i class="acsb-box-icon acsbi-help"></i>
                    </div>

                </a>


                <a class="acsb-option-item acsb-option-position acsb-option-position-right" role="button"
                    data-acsb-option="position" href="https://shamir.localtimeline.com/#" aria-label="שינוי כיוון ממשק"
                    data-acsb-tooltip="שינוי כיוון ממשק">

                    <div class="acsb-make-center acsb-make-center-relative">
                        <i class="acsb-box-icon acsbi-position"></i>
                    </div>

                </a>


                <a class="acsb-option-item acsb-option-hide acsb-option-position-right"
                    data-acsb-popup-trigger="acsb-hide-popup" role="button" data-acsb-option="hide"
                    href="https://shamir.localtimeline.com/#" aria-label="הסתרת ממשק נגישות"
                    data-acsb-tooltip="הסתרת ממשק נגישות">

                    <div class="acsb-make-center acsb-make-center-relative">
                        <i class="acsb-box-icon acsbi-hide"></i>
                    </div>

                </a>


            </div>
            <div class="acsb-hero acsb-bg-lead">

                <div class="acsb-hero-title-box">
                    <i class="acsbi-person"></i>
                    <span class="acsb-hero-title acsb-align-middle">התאמות נגישות באתר</span>
                </div>

                <div class="acsb-quick-buttons">

                    <ul class="acsb-clearfix">


                        <li class="acsb-quick-button-wrapper-virtual-keyboard">

                            <div class="acsb-quick-button acsb-quick-button-virtual-keyboard" role="button" tabindex="0"
                                aria-pressed="false" data-acsb-tooltip="הפעלת מקלדת וירטואלית בעת כתיבה"
                                data-acsb-action-message="מקלדת וירטואלית הופעלה בהצלחה. בכל פעם שתידרשו להזין תוכן בשדה, תופיע מקלדת וירטואלית מתחת לשדה באופן אוטומטי."
                                data-acsb-action="virtualKeyboard">
                                <i class="acsbi-keyboard"></i>
                                <span class="acsb-align-middle">מקלדת וירטואלית</span>
                            </div>

                        </li>


                        <li class="acsb-quick-button-wrapper-access-mode">

                            <div class="acsb-quick-button acsb-quick-button-access-mode" role="button" tabindex="0"
                                aria-pressed="false" data-acsb-tooltip="הפעלת התאמה אוטומטית לניווט מקלדת וקורא מסך"
                                data-acsb-action-message="התאמות נגישות הופעלו ונשמרו בזכרון. לפתיחת תפריט ניווט מהיר, לחצו Alt+1 מכל מקום באתר."
                                data-acsb-action="accessMode">
                                <i class="acsbi-accessibility"></i>
                                <span class="acsb-align-middle">הפעלת מצב נגיש</span>
                            </div>

                        </li>


                        <li class="acsb-quick-button-wrapper-stop-animations">

                            <div class="acsb-quick-button acsb-quick-button-stop-animations" role="button" tabindex="0"
                                aria-pressed="false" data-acsb-tooltip="עצירת אנימציות" data-acsb-action-message=""
                                data-acsb-action="stopAnimations">
                                <i class="acsbi-animations"></i>
                                <span class="acsb-align-middle">עצירת אנימציות</span>
                            </div>

                        </li>


                    </ul>

                </div>

            </div>
            <div class="acsb-main-options">

                <div class="acsb-search">

                    <form class="acsb-form" data-acsb-search="form" enctype="multipart/form-data"
                        action="https://shamir.localtimeline.com/#" method="POST">

                        <input type="text" tabindex="0" name="acsb_search" autocomplete="off"
                            placeholder="חפשו במילון אונליין..." data-acsb-tooltip="חפשו במילון אונליין..."
                            aria-label="חפשו במילון אונליין...">
                        <i class="acsbi-search"></i>
                    </form>

                </div>

                <div class="acsb-search-results-wrapper" data-search-results="wrapper">

                    <span role="button" tabindex="0" data-search-results="close" data-acsb-tooltip="סגירה"
                        aria-label="סגירה" class="acsb-search-close">
                        <i class="acsbi-close"></i>
                    </span>

                    <div class="acsb-search-results" data-search-results="list"></div>

                </div>

                <div data-acsb-actions-box="textAdjustments"
                    class="acsb-actions-box acsb-active acsb-clearfix acsb-actions-box-textAdjustments">

                    <div class="acsb-actions-box-title">
                        <span class="acsb-icon acsb-align-middle"><i class="acsbi-stack_text"></i></span>
                        <span class="acsb-title acsb-align-middle">התאמות טקסטים ותוכן</span>
                        <span class="acsb-chevron" data-acsb-collapse="textAdjustments"><i
                                class="acsbi-chevron_down"></i></span>
                    </div>

                    <div class="acsb-actions-group acsb-clearfix">



                        <div class="acsb-action-box acsb-action-box-fontSize acsb-action-box-big " tabindex="0"
                            data-acsb-action="fontSize" data-acsb-action-group="" data-acsb-action-message=""
                            data-acsb-action-css-property="font-size" data-acsb-action-specific-element=""
                            data-acsb-action-specific-increment="0">

                            <div class="acsb-make-center">

                                <i class="acsb-box-icon acsbi-font_size"></i>
                                <span class="acsb-box-title">הגדלה והקטנת הגופן</span>

                                <div class="acsb-big-box-element acsb-big-box-element-range">
                                    <div class="acsb-range" data-acsb-range-slider="">

                                        <div class="acsb-range-plus acsb-range-button acsb-bg-lead"
                                            data-acsb-range-action="increase" role="button" tabindex="0"
                                            data-acsb-tooltip="הוספה" aria-label="הוספה">
                                            <span class="acsb-make-center">+</span>
                                        </div>

                                        <div class="acsb-range-base acsb-color-lead" data-acsb-range-current-number="">0
                                        </div>

                                        <div class="acsb-range-minus acsb-range-button acsb-bg-lead"
                                            data-acsb-range-action="decrease" tabindex="0" aria-label="החסרה"
                                            data-acsb-tooltip="החסרה" role="button">
                                            <span class="acsb-make-center">-</span>
                                        </div>

                                    </div>
                                </div>

                            </div>

                        </div>




                        <div class="acsb-action-box acsb-action-box-readableFont  " role="button" aria-pressed="false"
                            tabindex="0" data-acsb-action="readableFont" data-acsb-action-group=""
                            data-acsb-action-message="" data-acsb-action-css-property=""
                            data-acsb-action-specific-element="" data-acsb-action-specific-increment="0">

                            <div class="acsb-make-center">

                                <i class="acsb-box-icon acsbi-font"></i>
                                <span class="acsb-box-title">גופן קריא</span>


                            </div>

                        </div>




                        <div class="acsb-action-box acsb-action-box-emphasizeTitles  " role="button"
                            aria-pressed="false" tabindex="0" data-acsb-action="emphasizeTitles"
                            data-acsb-action-group="" data-acsb-action-message="" data-acsb-action-css-property=""
                            data-acsb-action-specific-element="" data-acsb-action-specific-increment="0">

                            <div class="acsb-make-center">

                                <i class="acsb-box-icon acsbi-marker"></i>
                                <span class="acsb-box-title">הדגשת כותרות</span>


                            </div>

                        </div>




                        <div class="acsb-action-box acsb-action-box-emphasizeLinks  " role="button" aria-pressed="false"
                            tabindex="0" data-acsb-action="emphasizeLinks" data-acsb-action-group=""
                            data-acsb-action-message="" data-acsb-action-css-property=""
                            data-acsb-action-specific-element="" data-acsb-action-specific-increment="0">

                            <div class="acsb-make-center">

                                <i class="acsb-box-icon acsbi-links"></i>
                                <span class="acsb-box-title">הדגשת קישורים</span>


                            </div>

                        </div>




                        <div class="acsb-action-box acsb-action-box-magnifier  " role="button" aria-pressed="false"
                            tabindex="0" data-acsb-action="magnifier" data-acsb-action-group=""
                            data-acsb-action-message="במעבר עכבר, טקסטים יופיעו בחלונית מוגדלת ומופרדת!"
                            data-acsb-action-css-property="" data-acsb-action-specific-element=""
                            data-acsb-action-specific-increment="0">

                            <div class="acsb-make-center">

                                <i class="acsb-box-icon acsbi-magnifier"></i>
                                <span class="acsb-box-title">טקסט זכוכית מגדלת</span>


                            </div>

                        </div>




                        <div class="acsb-action-box acsb-action-box-zoom acsb-action-box-big " tabindex="0"
                            data-acsb-action="zoom" data-acsb-action-group="" data-acsb-action-message=""
                            data-acsb-action-css-property="zoom" data-acsb-action-specific-element="body"
                            data-acsb-action-specific-increment="25">

                            <div class="acsb-make-center">

                                <i class="acsb-box-icon acsbi-zoom"></i>
                                <span class="acsb-box-title">התאמת התוכן</span>

                                <div class="acsb-big-box-element acsb-big-box-element-range">
                                    <div class="acsb-range" data-acsb-range-slider="">

                                        <div class="acsb-range-plus acsb-range-button acsb-bg-lead"
                                            data-acsb-range-action="increase" role="button" tabindex="0"
                                            data-acsb-tooltip="הוספה" aria-label="הוספה">
                                            <span class="acsb-make-center">+</span>
                                        </div>

                                        <div class="acsb-range-base acsb-color-lead" data-acsb-range-current-number="">0
                                        </div>

                                        <div class="acsb-range-minus acsb-range-button acsb-bg-lead"
                                            data-acsb-range-action="decrease" tabindex="0" aria-label="החסרה"
                                            data-acsb-tooltip="החסרה" role="button">
                                            <span class="acsb-make-center">-</span>
                                        </div>

                                    </div>
                                </div>

                            </div>

                        </div>




                        <div class="acsb-action-box acsb-action-box-textAlignCenter  " role="button"
                            aria-pressed="false" tabindex="0" data-acsb-action="textAlignCenter"
                            data-acsb-action-group="textAlign" data-acsb-action-message=""
                            data-acsb-action-css-property="" data-acsb-action-specific-element=""
                            data-acsb-action-specific-increment="0">

                            <div class="acsb-make-center">

                                <i class="acsb-box-icon acsbi-align_center"></i>
                                <span class="acsb-box-title">יישור למרכז</span>


                            </div>

                        </div>




                        <div class="acsb-action-box acsb-action-box-wordSpacing acsb-action-box-big " tabindex="0"
                            data-acsb-action="wordSpacing" data-acsb-action-group="" data-acsb-action-message=""
                            data-acsb-action-css-property="word-spacing" data-acsb-action-specific-element=""
                            data-acsb-action-specific-increment="0.2">

                            <div class="acsb-make-center">

                                <i class="acsb-box-icon acsbi-word_spacing"></i>
                                <span class="acsb-box-title">שינוי מרווח בין מילים</span>

                                <div class="acsb-big-box-element acsb-big-box-element-range">
                                    <div class="acsb-range" data-acsb-range-slider="">

                                        <div class="acsb-range-plus acsb-range-button acsb-bg-lead"
                                            data-acsb-range-action="increase" role="button" tabindex="0"
                                            data-acsb-tooltip="הוספה" aria-label="הוספה">
                                            <span class="acsb-make-center">+</span>
                                        </div>

                                        <div class="acsb-range-base acsb-color-lead" data-acsb-range-current-number="">0
                                        </div>

                                        <div class="acsb-range-minus acsb-range-button acsb-bg-lead"
                                            data-acsb-range-action="decrease" tabindex="0" aria-label="החסרה"
                                            data-acsb-tooltip="החסרה" role="button">
                                            <span class="acsb-make-center">-</span>
                                        </div>

                                    </div>
                                </div>

                            </div>

                        </div>




                        <div class="acsb-action-box acsb-action-box-textAlignLeft  " role="button" aria-pressed="false"
                            tabindex="0" data-acsb-action="textAlignLeft" data-acsb-action-group="textAlign"
                            data-acsb-action-message="" data-acsb-action-css-property=""
                            data-acsb-action-specific-element="" data-acsb-action-specific-increment="0">

                            <div class="acsb-make-center">

                                <i class="acsb-box-icon acsbi-align_left"></i>
                                <span class="acsb-box-title">יישור לשמאל</span>


                            </div>

                        </div>




                        <div class="acsb-action-box acsb-action-box-letterSpacing acsb-action-box-big " tabindex="0"
                            data-acsb-action="letterSpacing" data-acsb-action-group="" data-acsb-action-message=""
                            data-acsb-action-css-property="letter-spacing" data-acsb-action-specific-element=""
                            data-acsb-action-specific-increment="2">

                            <div class="acsb-make-center">

                                <i class="acsb-box-icon acsbi-letter_spacing"></i>
                                <span class="acsb-box-title">שינוי מרווח בין אותיות</span>

                                <div class="acsb-big-box-element acsb-big-box-element-range">
                                    <div class="acsb-range" data-acsb-range-slider="">

                                        <div class="acsb-range-plus acsb-range-button acsb-bg-lead"
                                            data-acsb-range-action="increase" role="button" tabindex="0"
                                            data-acsb-tooltip="הוספה" aria-label="הוספה">
                                            <span class="acsb-make-center">+</span>
                                        </div>

                                        <div class="acsb-range-base acsb-color-lead" data-acsb-range-current-number="">0
                                        </div>

                                        <div class="acsb-range-minus acsb-range-button acsb-bg-lead"
                                            data-acsb-range-action="decrease" tabindex="0" aria-label="החסרה"
                                            data-acsb-tooltip="החסרה" role="button">
                                            <span class="acsb-make-center">-</span>
                                        </div>

                                    </div>
                                </div>

                            </div>

                        </div>




                        <div class="acsb-action-box acsb-action-box-textAlignRight  " role="button" aria-pressed="false"
                            tabindex="0" data-acsb-action="textAlignRight" data-acsb-action-group="textAlign"
                            data-acsb-action-message="" data-acsb-action-css-property=""
                            data-acsb-action-specific-element="" data-acsb-action-specific-increment="0">

                            <div class="acsb-make-center">

                                <i class="acsb-box-icon acsbi-align_right"></i>
                                <span class="acsb-box-title">יישור לימין</span>


                            </div>

                        </div>




                        <div class="acsb-action-box acsb-action-box-lineHeight acsb-action-box-big " tabindex="0"
                            data-acsb-action="lineHeight" data-acsb-action-group="" data-acsb-action-message=""
                            data-acsb-action-css-property="line-height" data-acsb-action-specific-element=""
                            data-acsb-action-specific-increment="0.1">

                            <div class="acsb-make-center">

                                <i class="acsb-box-icon acsbi-line_height"></i>
                                <span class="acsb-box-title">שינוי מרווח בין שורות</span>

                                <div class="acsb-big-box-element acsb-big-box-element-range">
                                    <div class="acsb-range" data-acsb-range-slider="">

                                        <div class="acsb-range-plus acsb-range-button acsb-bg-lead"
                                            data-acsb-range-action="increase" role="button" tabindex="0"
                                            data-acsb-tooltip="הוספה" aria-label="הוספה">
                                            <span class="acsb-make-center">+</span>
                                        </div>

                                        <div class="acsb-range-base acsb-color-lead" data-acsb-range-current-number="">0
                                        </div>

                                        <div class="acsb-range-minus acsb-range-button acsb-bg-lead"
                                            data-acsb-range-action="decrease" tabindex="0" aria-label="החסרה"
                                            data-acsb-tooltip="החסרה" role="button">
                                            <span class="acsb-make-center">-</span>
                                        </div>

                                    </div>
                                </div>

                            </div>

                        </div>




                        <div class="acsb-action-box acsb-action-box-textAlignJustify  " role="button"
                            aria-pressed="false" tabindex="0" data-acsb-action="textAlignJustify"
                            data-acsb-action-group="textAlign" data-acsb-action-message=""
                            data-acsb-action-css-property="" data-acsb-action-specific-element=""
                            data-acsb-action-specific-increment="0">

                            <div class="acsb-make-center">

                                <i class="acsb-box-icon acsbi-align_justify"></i>
                                <span class="acsb-box-title">יישור מוחלט</span>


                            </div>

                        </div>



                    </div>

                </div>


                <div data-acsb-actions-box="colorAdjustments"
                    class="acsb-actions-box acsb-active acsb-clearfix acsb-actions-box-colorAdjustments">

                    <div class="acsb-actions-box-title">
                        <span class="acsb-icon acsb-align-middle"><i class="acsbi-colors"></i></span>
                        <span class="acsb-title acsb-align-middle">התאמות צבעים ותצוגה</span>
                        <span class="acsb-chevron" data-acsb-collapse="colorAdjustments"><i
                                class="acsbi-chevron_down"></i></span>
                    </div>

                    <div class="acsb-actions-group acsb-clearfix">



                        <div class="acsb-action-box acsb-action-box-invertContrast  " role="button" aria-pressed="false"
                            tabindex="0" data-acsb-action="invertContrast" data-acsb-action-group="contrast"
                            data-acsb-action-message="" data-acsb-action-css-property=""
                            data-acsb-action-specific-element="" data-acsb-action-specific-increment="0">

                            <div class="acsb-make-center">

                                <i class="acsb-box-icon acsbi-invert"></i>
                                <span class="acsb-box-title">צבעים מנוגדים</span>


                            </div>

                        </div>




                        <div class="acsb-action-box acsb-action-box-highContrast  " role="button" aria-pressed="false"
                            tabindex="0" data-acsb-action="highContrast" data-acsb-action-group="contrast"
                            data-acsb-action-message="" data-acsb-action-css-property=""
                            data-acsb-action-specific-element="" data-acsb-action-specific-increment="0">

                            <div class="acsb-make-center">

                                <i class="acsb-box-icon acsbi-high_contrast"></i>
                                <span class="acsb-box-title">ניגודיות גבוה</span>


                            </div>

                        </div>




                        <div class="acsb-action-box acsb-action-box-highSaturation  " role="button" aria-pressed="false"
                            tabindex="0" data-acsb-action="highSaturation" data-acsb-action-group="contrast"
                            data-acsb-action-message="" data-acsb-action-css-property=""
                            data-acsb-action-specific-element="" data-acsb-action-specific-increment="0">

                            <div class="acsb-make-center">

                                <i class="acsb-box-icon acsbi-high_saturation"></i>
                                <span class="acsb-box-title">רוויה גבוהה</span>


                            </div>

                        </div>




                        <div class="acsb-action-box acsb-action-box-darkContrast  " role="button" aria-pressed="false"
                            tabindex="0" data-acsb-action="darkContrast" data-acsb-action-group="contrast"
                            data-acsb-action-message="" data-acsb-action-css-property=""
                            data-acsb-action-specific-element="" data-acsb-action-specific-increment="0">

                            <div class="acsb-make-center">

                                <i class="acsb-box-icon acsbi-dark"></i>
                                <span class="acsb-box-title">ניגודיות כהה</span>


                            </div>

                        </div>




                        <div class="acsb-action-box acsb-action-box-textColor acsb-action-box-big " tabindex="0"
                            data-acsb-action="textColor" data-acsb-action-group="" data-acsb-action-message=""
                            data-acsb-action-css-property="" data-acsb-action-specific-element=""
                            data-acsb-action-specific-increment="0">

                            <div class="acsb-make-center">

                                <i class="acsb-box-icon acsbi-picker"></i>
                                <span class="acsb-box-title">שינוי צבע הטקסטים</span>

                                <div class="acsb-big-box-element acsb-big-box-element-color">

                                    <div class="acsb-color-box">


                                        <span data-acsb-color="#0076B4" class="acsb-color-selection" role="button"
                                            tabindex="0" aria-label="שינוי לצבע כחול" data-acsb-tooltip"שינוי="" לצבע=""
                                            כחול"="" style="background-color: #0076B4 !important;"></span>


                                        <span data-acsb-color="#7A549C" class="acsb-color-selection" role="button"
                                            tabindex="0" aria-label="שינוי לצבע סגול" data-acsb-tooltip"שינוי="" לצבע=""
                                            סגול"="" style="background-color: #7A549C !important;"></span>


                                        <span data-acsb-color="#C83733" class="acsb-color-selection" role="button"
                                            tabindex="0" aria-label="שינוי לצבע אדום" data-acsb-tooltip"שינוי="" לצבע=""
                                            אדום"="" style="background-color: #C83733 !important;"></span>


                                        <span data-acsb-color="#D07021" class="acsb-color-selection" role="button"
                                            tabindex="0" aria-label="שינוי לצבע כתום" data-acsb-tooltip"שינוי="" לצבע=""
                                            כתום"="" style="background-color: #D07021 !important;"></span>


                                        <span data-acsb-color="#26999F" class="acsb-color-selection" role="button"
                                            tabindex="0" aria-label="שינוי לצבע תכלת" data-acsb-tooltip"שינוי="" לצבע=""
                                            תכלת"="" style="background-color: #26999F !important;"></span>


                                        <span data-acsb-color="#4D7831" class="acsb-color-selection" role="button"
                                            tabindex="0" aria-label="שינוי לצבע ירוק" data-acsb-tooltip"שינוי="" לצבע=""
                                            ירוק"="" style="background-color: #4D7831 !important;"></span>


                                        <span data-acsb-color="#ffffff" class="acsb-color-selection" role="button"
                                            tabindex="0" aria-label="שינוי לצבע WHITE" data-acsb-tooltip"שינוי=""
                                            לצבע="" white"="" style="background-color: #ffffff !important;"></span>


                                        <span data-acsb-color="#000000" class="acsb-color-selection" role="button"
                                            tabindex="0" aria-label="שינוי לצבע שחור" data-acsb-tooltip"שינוי="" לצבע=""
                                            שחור"="" style="background-color: #000000 !important;"></span>


                                        <div class="disable" role="button" tabindex="0" data-acsb-color="disable">
                                            ביטול </div>

                                    </div>
                                </div>

                            </div>

                        </div>




                        <div class="acsb-action-box acsb-action-box-monochrome  " role="button" aria-pressed="false"
                            tabindex="0" data-acsb-action="monochrome" data-acsb-action-group="contrast"
                            data-acsb-action-message="" data-acsb-action-css-property=""
                            data-acsb-action-specific-element="" data-acsb-action-specific-increment="0">

                            <div class="acsb-make-center">

                                <i class="acsb-box-icon acsbi-monochrome"></i>
                                <span class="acsb-box-title">גווני אפור</span>


                            </div>

                        </div>




                        <div class="acsb-action-box acsb-action-box-titleColor acsb-action-box-big " tabindex="0"
                            data-acsb-action="titleColor" data-acsb-action-group="" data-acsb-action-message=""
                            data-acsb-action-css-property="" data-acsb-action-specific-element=""
                            data-acsb-action-specific-increment="0">

                            <div class="acsb-make-center">

                                <i class="acsb-box-icon acsbi-picker"></i>
                                <span class="acsb-box-title">שינוי צבע כותרות</span>

                                <div class="acsb-big-box-element acsb-big-box-element-color">

                                    <div class="acsb-color-box">


                                        <span data-acsb-color="#0076B4" class="acsb-color-selection" role="button"
                                            tabindex="0" aria-label="שינוי לצבע כחול" data-acsb-tooltip"שינוי="" לצבע=""
                                            כחול"="" style="background-color: #0076B4 !important;"></span>


                                        <span data-acsb-color="#7A549C" class="acsb-color-selection" role="button"
                                            tabindex="0" aria-label="שינוי לצבע סגול" data-acsb-tooltip"שינוי="" לצבע=""
                                            סגול"="" style="background-color: #7A549C !important;"></span>


                                        <span data-acsb-color="#C83733" class="acsb-color-selection" role="button"
                                            tabindex="0" aria-label="שינוי לצבע אדום" data-acsb-tooltip"שינוי="" לצבע=""
                                            אדום"="" style="background-color: #C83733 !important;"></span>


                                        <span data-acsb-color="#D07021" class="acsb-color-selection" role="button"
                                            tabindex="0" aria-label="שינוי לצבע כתום" data-acsb-tooltip"שינוי="" לצבע=""
                                            כתום"="" style="background-color: #D07021 !important;"></span>


                                        <span data-acsb-color="#26999F" class="acsb-color-selection" role="button"
                                            tabindex="0" aria-label="שינוי לצבע תכלת" data-acsb-tooltip"שינוי="" לצבע=""
                                            תכלת"="" style="background-color: #26999F !important;"></span>


                                        <span data-acsb-color="#4D7831" class="acsb-color-selection" role="button"
                                            tabindex="0" aria-label="שינוי לצבע ירוק" data-acsb-tooltip"שינוי="" לצבע=""
                                            ירוק"="" style="background-color: #4D7831 !important;"></span>


                                        <span data-acsb-color="#ffffff" class="acsb-color-selection" role="button"
                                            tabindex="0" aria-label="שינוי לצבע WHITE" data-acsb-tooltip"שינוי=""
                                            לצבע="" white"="" style="background-color: #ffffff !important;"></span>


                                        <span data-acsb-color="#000000" class="acsb-color-selection" role="button"
                                            tabindex="0" aria-label="שינוי לצבע שחור" data-acsb-tooltip"שינוי="" לצבע=""
                                            שחור"="" style="background-color: #000000 !important;"></span>


                                        <div class="disable" role="button" tabindex="0" data-acsb-color="disable">
                                            ביטול </div>

                                    </div>
                                </div>

                            </div>

                        </div>




                        <div class="acsb-action-box acsb-action-box-lightContrast  " role="button" aria-pressed="false"
                            tabindex="0" data-acsb-action="lightContrast" data-acsb-action-group="contrast"
                            data-acsb-action-message="" data-acsb-action-css-property=""
                            data-acsb-action-specific-element="" data-acsb-action-specific-increment="0">

                            <div class="acsb-make-center">

                                <i class="acsb-box-icon acsbi-light"></i>
                                <span class="acsb-box-title">ניגודיות בהירה</span>


                            </div>

                        </div>




                        <div class="acsb-action-box acsb-action-box-backgroundColor acsb-action-box-big " tabindex="0"
                            data-acsb-action="backgroundColor" data-acsb-action-group="" data-acsb-action-message=""
                            data-acsb-action-css-property="" data-acsb-action-specific-element=""
                            data-acsb-action-specific-increment="0">

                            <div class="acsb-make-center">

                                <i class="acsb-box-icon acsbi-picker"></i>
                                <span class="acsb-box-title">שינוי צבע הרקע</span>

                                <div class="acsb-big-box-element acsb-big-box-element-color">

                                    <div class="acsb-color-box">


                                        <span data-acsb-color="#0076B4" class="acsb-color-selection" role="button"
                                            tabindex="0" aria-label="שינוי לצבע כחול" data-acsb-tooltip"שינוי="" לצבע=""
                                            כחול"="" style="background-color: #0076B4 !important;"></span>


                                        <span data-acsb-color="#7A549C" class="acsb-color-selection" role="button"
                                            tabindex="0" aria-label="שינוי לצבע סגול" data-acsb-tooltip"שינוי="" לצבע=""
                                            סגול"="" style="background-color: #7A549C !important;"></span>


                                        <span data-acsb-color="#C83733" class="acsb-color-selection" role="button"
                                            tabindex="0" aria-label="שינוי לצבע אדום" data-acsb-tooltip"שינוי="" לצבע=""
                                            אדום"="" style="background-color: #C83733 !important;"></span>


                                        <span data-acsb-color="#D07021" class="acsb-color-selection" role="button"
                                            tabindex="0" aria-label="שינוי לצבע כתום" data-acsb-tooltip"שינוי="" לצבע=""
                                            כתום"="" style="background-color: #D07021 !important;"></span>


                                        <span data-acsb-color="#26999F" class="acsb-color-selection" role="button"
                                            tabindex="0" aria-label="שינוי לצבע תכלת" data-acsb-tooltip"שינוי="" לצבע=""
                                            תכלת"="" style="background-color: #26999F !important;"></span>


                                        <span data-acsb-color="#4D7831" class="acsb-color-selection" role="button"
                                            tabindex="0" aria-label="שינוי לצבע ירוק" data-acsb-tooltip"שינוי="" לצבע=""
                                            ירוק"="" style="background-color: #4D7831 !important;"></span>


                                        <span data-acsb-color="#ffffff" class="acsb-color-selection" role="button"
                                            tabindex="0" aria-label="שינוי לצבע WHITE" data-acsb-tooltip"שינוי=""
                                            לצבע="" white"="" style="background-color: #ffffff !important;"></span>


                                        <span data-acsb-color="#000000" class="acsb-color-selection" role="button"
                                            tabindex="0" aria-label="שינוי לצבע שחור" data-acsb-tooltip"שינוי="" לצבע=""
                                            שחור"="" style="background-color: #000000 !important;"></span>


                                        <div class="disable" role="button" tabindex="0" data-acsb-color="disable">
                                            ביטול </div>

                                    </div>
                                </div>

                            </div>

                        </div>



                    </div>

                </div>


                <div data-acsb-actions-box="navigationAdjustments"
                    class="acsb-actions-box acsb-active acsb-clearfix acsb-actions-box-navigationAdjustments">

                    <div class="acsb-actions-box-title">
                        <span class="acsb-icon acsb-align-middle"><i class="acsbi-compass"></i></span>
                        <span class="acsb-title acsb-align-middle">התאמות ניווט והתמצאות</span>
                        <span class="acsb-chevron" data-acsb-collapse="navigationAdjustments"><i
                                class="acsbi-chevron_down"></i></span>
                    </div>

                    <div class="acsb-actions-group acsb-clearfix">



                        <div class="acsb-action-box acsb-action-box-screenReader  " role="button" aria-pressed="false"
                            tabindex="0" data-acsb-action="screenReader" data-acsb-action-group=""
                            data-acsb-action-message="" data-acsb-action-css-property=""
                            data-acsb-action-specific-element="" data-acsb-action-specific-increment="0">

                            <div class="acsb-make-center">

                                <i class="acsb-box-icon acsbi-screen"></i>
                                <span class="acsb-box-title">התאמה לקורא מסך</span>


                            </div>

                        </div>




                        <div class="acsb-action-box acsb-action-box-keyNav  acsb-action-box-hide-mobile" role="button"
                            aria-pressed="false" tabindex="0" data-acsb-action="keyNav" data-acsb-action-group=""
                            data-acsb-action-message="ניווט מקלדת הופעל בהצלחה. הנכם יכולים לנווט באתר בעזרת מקש TAB במקלדת, או בעזרת המקשים הבאים: H = מעבר בין כותרות, K = מעבר בין קישורים, L = מעבר בין רשימות, I = מעבר בין פריטי רשימות ותפריט, T = מעבר בין טבלאות, G = מעבר בין תמונות, B = מעבר בין כפתורים, F = מעבר בין שדות טופס."
                            data-acsb-action-css-property="" data-acsb-action-specific-element=""
                            data-acsb-action-specific-increment="0">

                            <div class="acsb-make-center">

                                <i class="acsb-box-icon acsbi-tab"></i>
                                <span class="acsb-box-title">ניווט מקלדת</span>


                            </div>

                        </div>




                        <div class="acsb-action-box acsb-action-box-readMode  " role="button" aria-pressed="false"
                            tabindex="0" data-acsb-action="readMode" data-acsb-action-group=""
                            data-acsb-action-message="" data-acsb-action-css-property=""
                            data-acsb-action-specific-element="" data-acsb-action-specific-increment="0">

                            <div class="acsb-make-center">

                                <i class="acsb-box-icon acsbi-glasses"></i>
                                <span class="acsb-box-title">הפעלת מצב קריאה</span>


                            </div>

                        </div>




                        <div class="acsb-action-box acsb-action-box-hideImages  " role="button" aria-pressed="false"
                            tabindex="0" data-acsb-action="hideImages" data-acsb-action-group=""
                            data-acsb-action-message="" data-acsb-action-css-property=""
                            data-acsb-action-specific-element="" data-acsb-action-specific-increment="0">

                            <div class="acsb-make-center">

                                <i class="acsb-box-icon acsbi-hide_images"></i>
                                <span class="acsb-box-title">הסתרת תמונות</span>


                            </div>

                        </div>




                        <div class="acsb-action-box acsb-action-box-mute  " role="button" aria-pressed="false"
                            tabindex="0" data-acsb-action="mute" data-acsb-action-group="" data-acsb-action-message=""
                            data-acsb-action-css-property="" data-acsb-action-specific-element=""
                            data-acsb-action-specific-increment="0">

                            <div class="acsb-make-center">

                                <i class="acsb-box-icon acsbi-mute"></i>
                                <span class="acsb-box-title">השתקת צלילים</span>


                            </div>

                        </div>




                        <div class="acsb-action-box acsb-action-box-usefulLinks acsb-action-box-big " tabindex="0"
                            data-acsb-action="usefulLinks" data-acsb-action-group="" data-acsb-action-message=""
                            data-acsb-action-css-property="" data-acsb-action-specific-element=""
                            data-acsb-action-specific-increment="0">

                            <div class="acsb-make-center">

                                <i class="acsb-box-icon acsbi-linking"></i>
                                <span class="acsb-box-title">תפריט ניווט באתר</span>

                                <div class="acsb-big-box-element acsb-big-box-element-selecter">
                                    <div class="acsb-selecter" data-acsb-selecter="usefulLinks">
                                        <select aria-label="תפריט ניווט באתר" data-acsb-tooltip="תפריט ניווט באתר">
                                            <option selected="" disabled="" value="acsbDefault">בחרו אפשרות</option>
                                            <option value="homepage">עמוד הבית</option>
                                            <option value="top">ראש הדף</option>
                                            <option value="bottom">סוף הדף</option>
                                            <option value="content">תוכן מרכזי</option>
                                        </select>
                                    </div>
                                </div>

                            </div>

                        </div>




                        <div class="acsb-action-box acsb-action-box-letterNav  acsb-action-box-hide-mobile"
                            role="button" aria-pressed="false" tabindex="0" data-acsb-action="letterNav"
                            data-acsb-action-group=""
                            data-acsb-action-message="הנכם יכולים לנווט בעזרת המקשים הבאים ולחיצה על קונטרול: H = מעבר בין כותרות, K = מעבר בין קישורים, L = מעבר בין רשימות, I = מעבר בין פריטי רשימות ותפריט, T = מעבר בין טבלאות, G = מעבר בין תמונות, B = מעבר בין כפתורים, F = מעבר בין שדות טופס."
                            data-acsb-action-css-property="" data-acsb-action-specific-element=""
                            data-acsb-action-specific-increment="0">

                            <div class="acsb-make-center">

                                <i class="acsb-box-icon acsbi-letter_navigation"></i>
                                <span class="acsb-box-title">ניווט אותיות</span>


                            </div>

                        </div>




                        <div class="acsb-action-box acsb-action-box-emphasizeFocus  " role="button" aria-pressed="false"
                            tabindex="0" data-acsb-action="emphasizeFocus" data-acsb-action-group=""
                            data-acsb-action-message="" data-acsb-action-css-property=""
                            data-acsb-action-specific-element="" data-acsb-action-specific-increment="0">

                            <div class="acsb-make-center">

                                <i class="acsb-box-icon acsbi-highlight_focus"></i>
                                <span class="acsb-box-title">הדגשת פוקוס</span>


                            </div>

                        </div>




                        <div class="acsb-action-box acsb-action-box-emphasizeHover  acsb-action-box-hide-mobile"
                            role="button" aria-pressed="false" tabindex="0" data-acsb-action="emphasizeHover"
                            data-acsb-action-group="" data-acsb-action-message="" data-acsb-action-css-property=""
                            data-acsb-action-specific-element="" data-acsb-action-specific-increment="0">

                            <div class="acsb-make-center">

                                <i class="acsb-box-icon acsbi-highlight_hover"></i>
                                <span class="acsb-box-title">דגש במעבר עכבר</span>


                            </div>

                        </div>




                        <div class="acsb-action-box acsb-action-box-bigBlackCursor  acsb-action-box-hide-mobile"
                            role="button" aria-pressed="false" tabindex="0" data-acsb-action="bigBlackCursor"
                            data-acsb-action-group="cursor" data-acsb-action-message="" data-acsb-action-css-property=""
                            data-acsb-action-specific-element="" data-acsb-action-specific-increment="0">

                            <div class="acsb-make-center">

                                <i class="acsb-box-icon acsbi-cursor"></i>
                                <span class="acsb-box-title">סמן שחור גדול</span>


                            </div>

                        </div>




                        <div class="acsb-action-box acsb-action-box-bigWhiteCursor  acsb-action-box-hide-mobile"
                            role="button" aria-pressed="false" tabindex="0" data-acsb-action="bigWhiteCursor"
                            data-acsb-action-group="cursor" data-acsb-action-message="" data-acsb-action-css-property=""
                            data-acsb-action-specific-element="" data-acsb-action-specific-increment="0">

                            <div class="acsb-make-center">

                                <i class="acsb-box-icon acsbi-cursor2"></i>
                                <span class="acsb-box-title">סמן לבן גדול</span>


                            </div>

                        </div>




                        <div class="acsb-action-box acsb-action-box-print  " role="button" aria-pressed="false"
                            tabindex="0" data-acsb-action="print" data-acsb-action-group="" data-acsb-action-message=""
                            data-acsb-action-css-property="" data-acsb-action-specific-element=""
                            data-acsb-action-specific-increment="0">

                            <div class="acsb-make-center">

                                <i class="acsb-box-icon acsbi-print"></i>
                                <span class="acsb-box-title">הדפסה נגישה</span>


                            </div>

                        </div>



                    </div>

                </div>

            </div>
        </div>


        <div class="acsb-footer">

            <div class="acsb-actions acsb-clearfix">

                <ul role="menu">

                    <li role="menuitem" aria-label="שלחו משוב">

                        <div class="acsb-footer-action" role="button" tabindex="0"
                            data-acsb-popup-trigger="acsb-feedback-popup" data-acsb-footer-action="feedback">

                            <span class="acsb-footer-action-icon acsb-border-color-lead"><i
                                    class="acsbi-envelop"></i></span>
                            <span class="acsb-footer-action-text">שלחו משוב</span>

                        </div>

                    </li>

                    <li role="menuitem" data-acsb-quick-button="reset" aria-label="ביטול נגישות">
                        <div class="acsb-footer-action" role="button" tabindex="0" data-acsb-footer-action="refresh">
                            <span class="acsb-footer-action-icon acsb-border-color-lead"><i
                                    class="acsbi-refresh"></i></span>
                            <span class="acsb-footer-action-text">ביטול נגישות</span>
                        </div>
                    </li>

                    <li role="menuitem" aria-label="הצהרת נגישות">

                        <div class="acsb-footer-action" role="button" tabindex="0"
                            data-acsb-popup-trigger="acsb-statement-popup" data-acsb-footer-action="statement">

                            <span class="acsb-footer-action-icon acsb-border-color-lead"><i
                                    class="acsbi-bullhorn"></i></span>
                            <span class="acsb-footer-action-text">הצהרת נגישות</span>
                        </div>
                    </li>

                </ul>

            </div>

            <div class="acsb-copyrights acsb-bg-lead">
                <span>ממשק נגישות האתר על ידי</span>
                <a rel="nofollow" href="https://nagishly.co.il/" tabindex="0" target="_blank">
                    נגישלי - נגישות אתרים </a>

            </div>

        </div>

        <div class="acsb-popup  acsb-language-popup" tabindex="0" data-acsb-popup="acsb-language-popup">

            <div class="acsb-popup-close-wide" data-acsb-popup-close="acsb-language-popup"></div>

            <div class="acsb-popup-content" role="dialog">

                <span class="acsb-popup-close" tabindex="0" role="button" aria-label="סגירה" data-acsb-tooltip="סגירה"
                    data-acsb-popup-close="acsb-language-popup">
                    <i class="acsbi-close"></i>
                </span>

                <div class="acsb-popup-content-holder" data-acsb-popup-content-holder="">

                    <div class="acsb-language-selection">

                        <span class="acsb-popup-title">בחרו את שפת ממשק הנגישות</span>

                        <ul class="acsb-clearfix">


                            <li class="">

                                <span role="button" tabindex="0" data-acsb-tooltip="English"
                                    class="acsb-full-link acsb-ignore-contrast" data-acsb-language="en"></span>

                                <div class="acsb-make-center">

                                    <img src="./index_files/en.svg" alt="English"> <span
                                        class="acsb-align-middle">English</span>

                                </div>

                            </li>


                            <li class="">

                                <span role="button" tabindex="0" data-acsb-tooltip="Español"
                                    class="acsb-full-link acsb-ignore-contrast" data-acsb-language="es"></span>

                                <div class="acsb-make-center">

                                    <img src="./index_files/es.svg" alt="Español"> <span
                                        class="acsb-align-middle">Español</span>

                                </div>

                            </li>


                            <li class="">

                                <span role="button" tabindex="0" data-acsb-tooltip="Deutsch"
                                    class="acsb-full-link acsb-ignore-contrast" data-acsb-language="de"></span>

                                <div class="acsb-make-center">

                                    <img src="./index_files/de.svg" alt="Deutsch"> <span
                                        class="acsb-align-middle">Deutsch</span>

                                </div>

                            </li>


                            <li class="">

                                <span role="button" tabindex="0" data-acsb-tooltip="Português"
                                    class="acsb-full-link acsb-ignore-contrast" data-acsb-language="pt"></span>

                                <div class="acsb-make-center">

                                    <img src="./index_files/pt.svg" alt="Português"> <span
                                        class="acsb-align-middle">Português</span>

                                </div>

                            </li>


                            <li class="">

                                <span role="button" tabindex="0" data-acsb-tooltip="Français"
                                    class="acsb-full-link acsb-ignore-contrast" data-acsb-language="fr"></span>

                                <div class="acsb-make-center">

                                    <img src="./index_files/fr.svg" alt="Français"> <span
                                        class="acsb-align-middle">Français</span>

                                </div>

                            </li>


                            <li class="">

                                <span role="button" tabindex="0" data-acsb-tooltip="Italiano"
                                    class="acsb-full-link acsb-ignore-contrast" data-acsb-language="it"></span>

                                <div class="acsb-make-center">

                                    <img src="./index_files/it.svg" alt="Italiano"> <span
                                        class="acsb-align-middle">Italiano</span>

                                </div>

                            </li>


                            <li class="acsb-active">

                                <span role="button" tabindex="0" data-acsb-tooltip="עברית"
                                    class="acsb-full-link acsb-ignore-contrast" data-acsb-language="he"></span>

                                <div class="acsb-make-center">

                                    <img src="./index_files/he.svg" alt="עברית"> <span
                                        class="acsb-align-middle">עברית</span>

                                </div>

                            </li>


                            <li class="">

                                <span role="button" tabindex="0" data-acsb-tooltip="繁體中文"
                                    class="acsb-full-link acsb-ignore-contrast" data-acsb-language="tw"></span>

                                <div class="acsb-make-center">

                                    <img src="./index_files/tw.svg" alt="繁體中文"> <span
                                        class="acsb-align-middle">繁體中文</span>

                                </div>

                            </li>


                            <li class="">

                                <span role="button" tabindex="0" data-acsb-tooltip="Pусский"
                                    class="acsb-full-link acsb-ignore-contrast" data-acsb-language="ru"></span>

                                <div class="acsb-make-center">

                                    <img src="./index_files/ru.svg" alt="Pусский"> <span
                                        class="acsb-align-middle">Pусский</span>

                                </div>

                            </li>


                            <li class="">

                                <span role="button" tabindex="0" data-acsb-tooltip="عربى"
                                    class="acsb-full-link acsb-ignore-contrast" data-acsb-language="ar"></span>

                                <div class="acsb-make-center">

                                    <img src="./index_files/ar.svg" alt="عربى"> <span
                                        class="acsb-align-middle">عربى</span>

                                </div>

                            </li>


                            <li class="">

                                <span role="button" tabindex="0" data-acsb-tooltip="عربى"
                                    class="acsb-full-link acsb-ignore-contrast" data-acsb-language="ua"></span>

                                <div class="acsb-make-center">

                                    <img src="./index_files/ua.svg" alt="عربى"> <span
                                        class="acsb-align-middle">عربى</span>

                                </div>

                            </li>


                            <li class="">

                                <span role="button" tabindex="0" data-acsb-tooltip="Nederlands"
                                    class="acsb-full-link acsb-ignore-contrast" data-acsb-language="nl"></span>

                                <div class="acsb-make-center">

                                    <img src="./index_files/nl.svg" alt="Nederlands"> <span
                                        class="acsb-align-middle">Nederlands</span>

                                </div>

                            </li>


                            <li class="">

                                <span role="button" tabindex="0" data-acsb-tooltip="繁體中文"
                                    class="acsb-full-link acsb-ignore-contrast" data-acsb-language="ct"></span>

                                <div class="acsb-make-center">

                                    <img src="./index_files/ct.svg" alt="繁體中文"> <span
                                        class="acsb-align-middle">繁體中文</span>

                                </div>

                            </li>


                            <li class="">

                                <span role="button" tabindex="0" data-acsb-tooltip="日本語"
                                    class="acsb-full-link acsb-ignore-contrast" data-acsb-language="jp"></span>

                                <div class="acsb-make-center">

                                    <img src="./index_files/jp.svg" alt="日本語"> <span
                                        class="acsb-align-middle">日本語</span>

                                </div>

                            </li>


                        </ul>

                    </div>
                </div>

            </div>

        </div>

        <div class="acsb-popup  acsb-feedback-popup" tabindex="0" data-acsb-popup="acsb-feedback-popup">

            <div class="acsb-popup-close-wide" data-acsb-popup-close="acsb-feedback-popup"></div>

            <div class="acsb-popup-content" role="dialog">

                <span class="acsb-popup-close" tabindex="0" role="button" aria-label="סגירה" data-acsb-tooltip="סגירה"
                    data-acsb-popup-close="acsb-feedback-popup">
                    <i class="acsbi-close"></i>
                </span>

                <div class="acsb-popup-content-holder" data-acsb-popup-content-holder="">

                    <div class="acsb-feedback">

                        <form class="acsb-form" action="https://shamir.localtimeline.com/#" method="POST"
                            data-acsb-form="feedback" enctype="multipart/form-data">

                            <div class="acsb-form-inner" data-acsb-form-element="inner">

                                <div class="acsb-form-title">שלחו משוב על נגישות האתר!</div>

                                <div class="acsb-form-field acsb-required">
                                    <label class="acsb-form-label" for="acsbForm_tmp[name]">שם מלא</label>
                                    <input required="" aria-required="true" id="acsbForm_tmp[name]" type="text"
                                        name="acsbForm_tmp[name]">
                                </div>

                                <div class="acsb-form-field acsb-required">
                                    <label class="acsb-form-label" for="acsbForm_tmp[email]">כתובת אימייל</label>
                                    <input required="" aria-required="true" type="email" name="acsbForm_tmp[email]"
                                        id="acsbForm_tmp[email]">
                                </div>

                                <div class="acsb-form-field acsb-required">
                                    <label class="acsb-form-label" for="acsbForm_tmp[phone]">מספר טלפון</label>
                                    <input required="" aria-required="true" type="tel" id="acsbForm_tmp[phone]"
                                        name="acsbForm_tmp[phone]">
                                </div>

                                <div class="acsb-form-field acsb-required">
                                    <label class="acsb-form-label" for="acsbForm_tmp[message]">ספרו לנו את דעתכם</label>
                                    <textarea required="" aria-required="true" id="acsbForm_tmp[message]"
                                        name="acsbForm_tmp[message]"></textarea>
                                </div>

                                <div class="acsb-form-field acsb-form-field-checkbox acsb-form-field-checkbox-warning">
                                    <input type="checkbox" required="" aria-required="true" id="acsbForm_tmp[agreement]"
                                        name="acsbForm_tmp[agreement]">
                                    <label class="acsb-form-label" for="acsbForm_tmp[agreement]">טופס זה הינו טופס משוב
                                        על נגישות האתר לאנשים עם מוגבלויות ולא טופס יצירת קשר עם העסק. פניה שאינה קשורה
                                        לנגישות האתר לאנשים עם מוגבלויות לא תקבל מענה. סמנו תיבה זו בכדי לאשר שאתם
                                        מבינים זאת.</label>
                                </div>

                                <button class="acsb-button acsb-button-full acsb-button-colorized" type="submit">
                                    שלחו טופס משוב <div class="acsb-loader-actual"></div>
                                </button>

                                <label for="acsbForm_tmp[malkodet]" style="display: none;">Malkodet</label>
                                <input type="hidden" id="acsbForm_tmp[malkodet]" name="acsbForm_tmp[malkodet]">
                                <input type="hidden" id="acsbForm_tmp[language]" name="acsbForm_tmp[language]"
                                    value="he">

                            </div>

                            <div class="acsb-form-success-container" data-acsb-form-element="success">
                                <span class="acsb-title">הודעתכם נשלחה בהצלחה!</span>
                            </div>

                        </form>

                    </div>
                </div>

            </div>

        </div>

        <div class="acsb-popup  acsb-statement-popup" tabindex="0" data-acsb-popup="acsb-statement-popup">
            <div class="acsb-popup-close-wide" data-acsb-popup-close="acsb-statement-popup"></div>
            <div class="acsb-popup-content" role="dialog">
            </div>
        </div>
</div>
    <script src="./js/gallery.js"></script>
    <script src="./js/jssor.slider-28.0.0.min.js"></script>
    <script src='./js/domBehaviours.js'></script>
    <script src="./js/timeLineController.js"></script>
    <script src="./js/sortingApiData.js"></script>
    <script src='./js/dataRender.js'></script>
    </body>

</html>