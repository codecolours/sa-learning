<?php 
/**
* Title: Video Box
* Description: Video Box
* Category: media
* Icon: format-video
* Keywords: video-box
* SupportsAlign: false
* Mode: edit
* PostTypes: page post
*/
?>

<?php 
$video_url = get_field( 'video_url' );
$video_type = get_field( 'video_type' );
$extraClassName = !empty($block['className']) ? ' ' . esc_attr($block['className']) : '';

function extract_video_id($url, $type) {
    if ($type === 'youtube') {
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches);
        return isset($matches[1]) ? $matches[1] : '';
    } elseif ($type === 'vimeo') {
        preg_match('/vimeo\.com\/(?:.*\/)?(\d+)/', $url, $matches);
        return isset($matches[1]) ? $matches[1] : '';
    }
    return '';
}

$video_id = extract_video_id($video_url, $video_type);
?>

<?php if ($video_id && $video_type): ?>

    <div data-block-id="<?php echo esc_attr($block['id']); ?>" class="block-content video-box-block<?php echo $extraClassName; ?>" data-video-type="<?php echo esc_attr($video_type); ?>">
        <div class="block-inner">
            <div class="video-container">
                <?php if ($video_type === 'youtube'): ?>
                    <iframe 
                        class="video-iframe"
                        src="https://www.youtube.com/embed/<?php echo esc_attr($video_id); ?>?autoplay=1&mute=1&controls=0&loop=1&playlist=<?php echo esc_attr($video_id); ?>&modestbranding=1&showinfo=0&rel=0&playsinline=1&disablekb=1&fs=0&iv_load_policy=3"
                        frameborder="0"
                        allow="autoplay; encrypted-media"
                        allowfullscreen
                        style="pointer-events: none;">
                    </iframe>
                    <div class="video-overlay"></div>
                <?php elseif ($video_type === 'vimeo'): ?>
                    <iframe 
                        class="video-iframe"
                        src="https://player.vimeo.com/video/<?php echo esc_attr($video_id); ?>?autoplay=1&muted=1&controls=0&loop=1&background=1&autopause=0"
                        frameborder="0"
                        allow="autoplay; fullscreen"
                        allowfullscreen>
                    </iframe>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php else: ?>

    <div class="block-content video-box-block<?php echo $extraClassName; ?>">
        <div class="block-inner">
            <p>Please add a video URL and select video type in block settings.</p>
        </div>
    </div>

<?php endif; ?>
