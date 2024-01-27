package youtube

import (
	"fmt"
	"net/url"
	"noads/client"
	"noads/providers/models"
	"regexp"
	"sort"
	"strings"
)

type Youtube struct {
	client.HttpClient
	endpoint string
}

func NewYoutube() *Youtube {
	return &Youtube{
		client.NewHttpClient(),
		"https://youtube-dl.wave.video/info",
	}
}

func (y *Youtube) CheckLink(link string) bool {
	pattern := `youtu.*be.*\/(watch\?v=|embed\/|v|shorts|)([^&#?]+)`
	re := regexp.MustCompile(pattern)
	match := re.FindStringSubmatch(link)
	return len(match) > 0
}

func (y *Youtube) GetLink(link string) (interface{}, error) {
	endpoint := fmt.Sprintf("%s?url=%s", y.endpoint, link)
	apiResponse := &YTApiResponse{}
	err := y.GetStruct(endpoint, nil, apiResponse)
	if err != nil {
		return nil, err
	}
	videoOnlyFormats := apiResponse.VideoOnly
	sort.Slice(videoOnlyFormats, func(i, j int) bool {
		return videoOnlyFormats[i].Height > videoOnlyFormats[j].Height
	})
	formats := apiResponse.Formats
	if len(formats) == 0 {
		return nil, fmt.Errorf("can not get video url")
	}
	sort.Slice(formats, func(i, j int) bool {
		return formats[i].Height > formats[j].Height
	})

	videoWithAudioFormats := make([]*YTVideoFormat, 0)
	for _, format := range formats {
		if format.AudioChannels != nil && format.Height >= 720 {
			videoWithAudioFormats = append(videoWithAudioFormats, &format)
		}
	}
	if len(videoWithAudioFormats) == 0 {
		return nil, fmt.Errorf("can not get video url")
	}
	sort.Slice(videoWithAudioFormats, func(i, j int) bool {
		return videoWithAudioFormats[i].Height > videoWithAudioFormats[j].Height
	})
	m3u8Videos := make([]*YTVideoFormat, 0)
	for _, format := range videoOnlyFormats {
		if format.ManifestUrl != nil && strings.Contains(*format.ManifestUrl, "m3u8") {
			m3u8Videos = append(m3u8Videos, &format)
		}
	}

	episode := make(map[string]string, 0)
	mod := models.Model{
		Link:      link,
		Name:      apiResponse.Title,
		Media:     make([]string, 0),
		Episode:   &episode,
		Thumbnail: &apiResponse.Thumbnail,
	}
	if len(m3u8Videos) > 0 {
		//sort by height desc
		sort.Slice(m3u8Videos, func(i, j int) bool {
			return m3u8Videos[i].Height > m3u8Videos[j].Height
		})
		//add the highest quality
		mod.Media = append(mod.Media, *m3u8Videos[0].ManifestUrl)
		return mod, nil
	}
	videoHeight := videoOnlyFormats[0].Height

	if videoWithAudioFormats[0].Height > videoHeight {
		videoHeight = videoWithAudioFormats[0].Height
	}

	mod.Media = append(mod.Media, videoWithAudioFormats[0].Url)
	if videoOnlyFormats[0].Height > 720 {
		note := fmt.Sprintf("%dp", videoOnlyFormats[0].Height)
		videoUrl, err := y.getDownloadLink(link, apiResponse.Title, apiResponse.Id, note)
		if err != nil {
			return nil, err
		}
		mod.Media = append(mod.Media, videoUrl)
	}
	fmt.Printf("mod: %v", mod)
	return mod, nil
}

func (y *Youtube) getDownloadLink(uri, title, id, note string) (string, error) {
	endpoint := "https://nearby.www-2048.com/mates/en/convert"

	headers := map[string]string{
		"Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
	}

	body := url.Values{}
	body.Add("url", uri)
	body.Add("title", title)
	body.Add("id", id)
	body.Add("note", note)
	body.Add("platform", "youtube")
	body.Add("ext", "mp4")
	body.Add("format", "137")

	type Response struct {
		Status   string `json:"status"`
		Download string `json:"downloadUrlX"`
	}

	response := &Response{}
	err := y.Post(endpoint, &headers, strings.NewReader(body.Encode()), response)
	if err != nil {
		return "", err
	}

	if response.Status != "success" {
		return "", fmt.Errorf("can not get video url")
	}
	return response.Download, nil

}
