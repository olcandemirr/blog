<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>{{ $title }}</title>
        <link>{{ route('categories.show', $category) }}</link>
        <description>{{ $description }}</description>
        <language>en-us</language>
        <pubDate>{{ now()->toRssString() }}</pubDate>
        <lastBuildDate>{{ $posts->first() ? $posts->first()->created_at->toRssString() : now()->toRssString() }}</lastBuildDate>
        <atom:link href="{{ url()->current() }}" rel="self" type="application/rss+xml" />
        
        @foreach($posts as $post)
            <item>
                <title>{{ $post->title }}</title>
                <link>{{ route('posts.show', $post) }}</link>
                <description><![CDATA[{!! Str::limit(strip_tags($post->content), 300) !!}]]></description>
                <category>{{ $post->category->name }}</category>
                <author>{{ $post->user->email }} ({{ $post->user->name }})</author>
                <guid>{{ route('posts.show', $post) }}</guid>
                <pubDate>{{ $post->created_at->toRssString() }}</pubDate>
            </item>
        @endforeach
    </channel>
</rss> 