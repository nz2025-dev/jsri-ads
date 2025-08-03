<style>
#age-modal, #nra-modal  {
    display: none;
}
#menuPanel{
    display:none;
}
.space-y-5{
    display:none;
}
@media (min-width: 1024px) {
    .lg\:w-9\/12 {
        width: 100% !important;
    }
}
@media (min-width: 1536px) {
    .container {
        max-width: 1500px !important;
    }
}
/* On tablets and smaller */
@media (max-width: 768px) {
    .container {
        width: 95%;
    }
}

/* On mobile phones */
@media (max-width: 480px) {
    .container {
        width: 80% !important;
        margin-left: 10px !important;
    }
}
.px-4 {
    padding-right: 4rem !important;
}
.bg-gradient-to-r{
    /* display:none; */
}
a.btn.btn-main[href*="login"] {
    display: none !important;
}
a[href*="ok-ads.com"] {
    display: none !important;
}
a[href*="hela-lanka.com"] {
    display: none !important;
}
a[href="https://whatsapp.com/channel/0029VaodbvjBA1epaFQ5X42y"] {
    display: none !important;
}
a[href="https://t.me/lnkaads"] {
    display: none !important;
}
a[href="https://x.com/LankaAdscom"] {
    display: none !important;
}
a[href="?url=https%3A%2F%2Flanka-ad.com%2Fagents"] {
    display: none !important;
}
.search{
    display: none !important;
}
#menuToggler{
     display: none !important;
}
/* main {
         margin-left: 4px !important;
    margin-right: 64px !important;
} */

html, body {
    overflow-x: hidden !important;
}
</style>
<?php
function absolute_url($base, $relative) {
    if (parse_url($relative, PHP_URL_SCHEME) != '') return $relative;
    $abs = $base . '/' . $relative;
    $abs = preg_replace('/\/+/', '/', $abs);
    return $abs;
}

// Use passed URL or default
//$baseUrl = "https://lanka-ad.com/";
if (isset($_GET['url']) && filter_var($_GET['url'], FILTER_VALIDATE_URL)) {
    $url = $_GET['url'];
    $baseUrl = parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST);
} else {
    $url = $baseUrl;
}

// Fetch the page
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');
$response = curl_exec($ch);
curl_close($ch);

if ($response === false) {
    die("Failed to fetch the page.");
}

// Fix relative links
$response = preg_replace_callback('/(href|src)=["\'](.*?)["\']/i', function($matches) use ($baseUrl) {
    $attr = $matches[1];
    $link = $matches[2];

    if (parse_url($link, PHP_URL_SCHEME) != '' || substr($link, 0, 2) == '//') {
        return $matches[0];
    }

    $absolute = rtrim($baseUrl, '/') . '/' . ltrim($link, '/');
    return $attr . '="' . $absolute . '"';
}, $response);

// Update all <a href> to go through your scraper
$response = preg_replace_callback('/href=["\'](.*?)["\']/i', function($matches) {
    $link = $matches[1];
    if (strpos($link, 'lanka-ad.com') !== false) {
        return 'href="?url=' . urlencode($link) . '"';
    } elseif (strpos($link, 'http') === 0) {
        return 'href="' . $link . '"';
    } else {
        return 'href="?url=' . urlencode($link) . '"';
    }
}, $response);

$response = str_replace(
    '<h2 class="text-white text-sm sm:text-base md:text-lg lg:text-xl font-bold">Lanka Ads</h2>',
    '<h2 class="text-white text-sm sm:text-base md:text-lg lg:text-xl font-bold">Hot Ads Lanka</h2>',
    $response
);
$response = str_replace(
    '<a href="?url=https%3A%2F%2Flanka-ad.com" class="text-red-600 hover:text-white">Lanka Ads.</a>',
    '<a href="?url=https://hotadslanka.com" class="text-red-600 hover:text-white">Hot Ads Lanka</a>',
    $response
);

// Output
echo $response;

?>
