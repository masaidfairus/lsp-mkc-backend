<?php

$bladeDir = 'resources/views/forms/IA';
$bladeFiles = scandir($bladeDir);

$dynamicTableCode = <<<HTML
    <tbody>
        @foreach(['Penyusun' => \$compilers ?? \$penyusun ?? [], 'Validator' => \$validators ?? \$validator ?? []] as \$status => \$items)
            @php 
                \$items = (array)\$items;
                \$count = max(2, count(\$items)); 
            @endphp
            @for(\$i = 0; \$i < \$count; \$i++)
                @php
                    \$item = \$items[\$i] ?? [];
                    \$name = \$item['name'] ?? \$item['nama'] ?? '';
                    \$reg = \$item['reg_number'] ?? \$item['nomor_met'] ?? '';
                    \$qr = \$item['qr_code'] ?? null;
                    \$sig = \$item['signature'] ?? null;
                    \$date = \$item['date'] ?? '';
                @endphp
                <tr>
                    @if(\$i === 0)
                        <td rowspan="{{ \$count }}" style="font-weight:bold; text-transform:uppercase;">{{ \$status }}</td>
                    @endif
                    <td style="text-align:center;">{{ \$i + 1 }}</td>
                    <td>{{ \$name }}</td>
                    <td style="text-align:center;">{{ \$reg }}</td>
                    <td style="height:45px; text-align:center; vertical-align:middle;">
                        @if(\$qr)
                            <img src="{{ \$qr }}" style="width:45px;height:45px;vertical-align:middle;">
                        @elseif(\$sig)
                            <img src="{{ \$sig }}" style="max-height:40px;vertical-align:middle;">
                        @endif
                        @if(\$date)
                            <br><span style="font-size:8pt;">{{ \$date }}</span>
                        @endif
                    </td>
                </tr>
            @endfor
        @endforeach
    </tbody>
HTML;

foreach ($bladeFiles as $filename) {
    if (str_ends_with($filename, '.blade.php') && str_starts_with($filename, 'ia')) {
        $filepath = $bladeDir . '/' . $filename;
        $content = file_get_contents($filepath);
        $original = $content;

        // Replace the entire hardcoded tbody block after </thead> for pv-table
        // It looks like:
        // </thead>
        // <tbody>
        //     <tr>
        //         <td rowspan="2" style="font-weight:bold;">PENYUSUN</td>...
        //     </tbody>
        $regex = '/<tbody>\s*<tr>\s*<td[^>]*>PENYUSUN<\/td>.*?<\/tbody>/s';
        if (preg_match($regex, $content)) {
            $content = preg_replace($regex, $dynamicTableCode, $content);
        }

        if ($content !== $original) {
            file_put_contents($filepath, $content);
            echo "Updated Hardcoded Tbody: {$filename}\n";
        }
    }
}
echo "Done hardcoded tbody update.\n";
