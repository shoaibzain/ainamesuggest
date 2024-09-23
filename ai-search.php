<?php
//////////////////
//AI Search Domain 
//////////////////
add_action('wp_ajax_nopriv_ai_domain_search', 'ai_domain_search');
add_action('wp_ajax_ai_domain_search', 'ai_domain_search');

require_once __DIR__ . '/vendor/autoload.php';
use Orhanerday\OpenAi\OpenAi;

function ai_domain_search() {
    if (isset($_POST['ai_search_domain'])) {
        $open_ai_key = '';
        $open_ai = new OpenAi($open_ai_key);

        $prompt = sanitize_text_field($_POST['ai_search_domain']);

        // Check cache or perform AI request
        $cache_key = 'ai_domain_' . md5($prompt);
        $cached_results = get_transient($cache_key);

        if ($cached_results) {
            wp_send_json_success($cached_results);
        } else {
            $complete = $open_ai->chat([
                'model' => 'gpt-4',
                'messages' => [
                    [
                        "role" => "system",
                        // "content" => "Generate unique and non-repetitive domain name ideas for a business described as: $prompt. Limit the output to between 5 to 8 suggestions. Exclude domain extensions and any additional content."
                        "content" => "Generate unique and non-repetitive business name ideas for a business described as: $prompt. Limit the output to between 5 to 8 suggestions. any additional content."
                    ],
                    [
                        "role" => "user",
                        "content" => "{$prompt}"
                    ],
                ],
                'temperature' => 1.0,
                'max_tokens' => 100,
                'frequency_penalty' => 0,
                'presence_penalty' => 0,
            ]);

            $response = json_decode($complete, true);
            $message_content = $response["choices"][0]["message"]["content"];
            $lines = explode("\n", $message_content);

            set_transient($cache_key, $lines, 60 * 5); // Cache for 5 minutes

            wp_send_json_success($lines);
        }
    } else {
        wp_send_json_error('No business provided');
    }
    wp_die();
}
