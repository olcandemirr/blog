{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:content="http://purl.org/rss/1.0/modules/content/">
    <channel>
        <title>{{ $title }}</title>
        <link>{{ $url }}</link>
        <description>{{ $description }}</description>
        <language>en-us</language>
        <pubDate>{{ now()->toRssString() }}</pubDate>
        <lastBuildDate>{{ $posts->first() ? $posts->first()->created_at->toRssString() : now()->toRssString() }}</lastBuildDate>
        <atom:link href="{{ $url }}" rel="self" type="application/rss+xml" />
        
        @foreach($posts as $post)
            <item>
                <title>{{ $post->title }}</title>
                <link>{{ route('posts.show', $post) }}</link>
                <guid>{{ route('posts.show', $post) }}</guid>
                <pubDate>{{ $post->created_at->toRssString() }}</pubDate>
                <description>{{ Str::limit(strip_tags($post->content), 200) }}</description>
                <content:encoded><![CDATA[{!! $post->content !!}]]></content:encoded>
                <author>{{ $post->user->email }} ({{ $post->user->name }})</author>
                <category>{{ $post->category->name }}</category>
            </item>
        @endforeach
    </channel>
</rss> 