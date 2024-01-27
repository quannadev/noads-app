package youtube

type YTApiResponse struct {
	Id         string          `json:"id"`
	ChannelId  string          `json:"channel_id"`
	Title      string          `json:"title"`
	Uploader   string          `json:"uploader"`
	UploaderId string          `json:"uploader_id"`
	Duration   int             `json:"duration"`
	ViewCount  int             `json:"view_count"`
	Thumbnail  string          `json:"thumbnail"`
	Formats    []YTVideoFormat `json:"formats"`
	VideoOnly  []YTVideoFormat `json:"formatsVideoOnly"`
}

type YTVideoFormat struct {
	Asr                int         `json:"asr"`
	Filesize           interface{} `json:"filesize"`
	FormatId           string      `json:"format_id"`
	FormatNote         string      `json:"format_note"`
	SourcePreference   int         `json:"source_preference"`
	Fps                int         `json:"fps"`
	AudioChannels      *int        `json:"audio_channels"`
	Height             int         `json:"height"`
	Quality            int         `json:"quality"`
	HasDrm             bool        `json:"has_drm"`
	Tbr                float64     `json:"tbr"`
	Url                string      `json:"url"`
	ManifestUrl        *string     `json:"manifest_url"`
	Width              int         `json:"width"`
	Language           string      `json:"language"`
	LanguagePreference int         `json:"language_preference"`
	Preference         interface{} `json:"preference"`
	Ext                string      `json:"ext"`
	Vcodec             string      `json:"vcodec"`
	Acodec             string      `json:"acodec"`
	DynamicRange       string      `json:"dynamic_range"`
	DownloaderOptions  struct {
		HttpChunkSize int `json:"http_chunk_size"`
	} `json:"downloader_options"`
	Protocol       string  `json:"protocol"`
	Resolution     string  `json:"resolution"`
	AspectRatio    float64 `json:"aspect_ratio"`
	FilesizeApprox int     `json:"filesize_approx"`
	HttpHeaders    struct {
		UserAgent      string `json:"User-Agent"`
		Accept         string `json:"Accept"`
		AcceptLanguage string `json:"Accept-Language"`
		SecFetchMode   string `json:"Sec-Fetch-Mode"`
	} `json:"http_headers"`
	VideoExt    string      `json:"video_ext"`
	AudioExt    string      `json:"audio_ext"`
	Vbr         interface{} `json:"vbr"`
	Abr         interface{} `json:"abr"`
	Format      string      `json:"format"`
	DownloadUrl string      `json:"downloadUrl"`
	Filename    string      `json:"filename"`
}
