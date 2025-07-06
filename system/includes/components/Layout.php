<?php
class Layout {
    public static function header($title, $backUrl = null) {
        $html = "<div class='flex justify-between items-center mb-6'>";
        $html .= "<h1 class='text-3xl font-bold'>$title</h1>";
        
        if ($backUrl) {
            $html .= "<a href='$backUrl' class='btn btn-outline'>";
            $html .= "<svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5 mr-2' viewBox='0 0 20 20' fill='currentColor'>";
            $html .= "<path fill-rule='evenodd' d='M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z' clip-rule='evenodd' />";
            $html .= "</svg>";
            $html .= "Back to List";
            $html .= "</a>";
        }
        
        $html .= "</div>";
        return $html;
    }

    public static function alert($type, $message) {
        $class = $type === 'success' ? 'alert-success' : 'alert-error';
        return "<div class='alert $class mb-6'>$message</div>";
    }

    public static function card($content) {
        $html = "<div class='card'>";
        $html .= "<div class='card-content'>";
        $html .= $content;
        $html .= "</div>";
        $html .= "</div>";
        return $html;
    }

    public static function table($headers, $rows, $actions = []) {
        $html = "<div class='bg-card text-card-foreground rounded-lg border shadow-sm'>";
        $html .= "<div class='overflow-x-auto'>";
        $html .= "<table class='w-full'>";
        
        // Headers
        $html .= "<thead><tr class='border-b bg-muted/50'>";
        foreach ($headers as $header) {
            $html .= "<th class='px-4 py-3 text-left font-medium'>$header</th>";
        }
        if (!empty($actions)) {
            $html .= "<th class='px-4 py-3 text-left font-medium'>Actions</th>";
        }
        $html .= "</tr></thead>";
        
        // Body
        $html .= "<tbody>";
        if (empty($rows)) {
            $colspan = count($headers) + (!empty($actions) ? 1 : 0);
            $html .= "<tr><td colspan='$colspan' class='px-4 py-3 text-center text-muted-foreground'>No records found</td></tr>";
        } else {
            foreach ($rows as $row) {
                $html .= "<tr class='border-b hover:bg-muted/50'>";
                foreach ($row as $cell) {
                    $html .= "<td class='px-4 py-3'>" . htmlspecialchars($cell) . "</td>";
                }
                
                if (!empty($actions)) {
                    $html .= "<td class='px-4 py-3'><div class='flex items-center gap-2'>";
                    foreach ($actions as $action) {
                        $url = str_replace(':id', $row['id'], $action['url']);
                        $html .= "<a href='$url' class='{$action['class']}'>{$action['text']}</a>";
                    }
                    $html .= "</div></td>";
                }
                
                $html .= "</tr>";
            }
        }
        $html .= "</tbody>";
        
        $html .= "</table>";
        $html .= "</div>";
        $html .= "</div>";
        
        return $html;
    }

    public static function pagination($current, $total, $url) {
        if ($total <= 1) return '';
        
        $html = "<div class='flex justify-center gap-2'>";
        
        if ($current > 1) {
            $html .= "<a href='{$url}?page=" . ($current - 1) . "' class='btn btn-outline'>Previous</a>";
        }
        
        for ($i = 1; $i <= $total; $i++) {
            $class = $i === $current ? 'btn-primary' : 'btn-outline';
            $html .= "<a href='{$url}?page=$i' class='btn $class'>$i</a>";
        }
        
        if ($current < $total) {
            $html .= "<a href='{$url}?page=" . ($current + 1) . "' class='btn btn-outline'>Next</a>";
        }
        
        $html .= "</div>";
        return $html;
    }
} 