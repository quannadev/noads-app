package film

import (
	"fmt"
	"noads/client"
	"noads/providers/models"
)

type Result struct {
	Status   bool      `json:"status"`
	Movie    *Movie    `json:"movie"`
	Episodes []Episode `json:"episodes"`
}

type Movie struct {
	Name      string `json:"name"`
	Slug      string `json:"slug"`
	Thumbnail string `json:"poster_url"`
}
type Episode struct {
	Server string      `json:"server_name"`
	Media  []MediaItem `json:"server_data"`
}
type MediaItem struct {
	Name      string `json:"name"`
	Slug      string `json:"slug"`
	Filename  string `json:"filename"`
	LinkEmbed string `json:"link_embed"`
	LinkM3U8  string `json:"link_m3u8"`
}
type OPhim struct {
	client.HttpClient
	endpoint string
}

func NewOPhim() *OPhim {
	return &OPhim{
		client.NewHttpClient(),
		"https://ophim1.com",
	}
}

func (o *OPhim) CheckLink(link string) bool {
	_, err := o.ParseUrl(link)
	if err != nil {
		return true
	}
	return false
}

func (o *OPhim) GetLink(link string) (interface{}, error) {
	results, err := o.GetFilmInfo(link)
	if err != nil {
		return nil, err
	}
	episodes := make(map[string]string, 0)
	for _, episode := range results.Episodes {
		for _, media := range episode.Media {
			episodes[media.Name] = media.LinkM3U8
		}
	}
	medias := make([]string, 0)
	for _, episode := range results.Episodes {
		firstMedia := episode.Media[0]
		medias = append(medias, firstMedia.LinkM3U8)
	}
	model := &models.Model{
		Name:      results.Movie.Name,
		Link:      link,
		Thumbnail: &results.Movie.Thumbnail,
		Episode:   &episodes,
		Media:     medias,
	}
	return model, nil
}

func (o *OPhim) GetFilmInfo(link string) (*Result, error) {
	linkSlug := o.GetSlugTitle(link)
	endpoint := o.endpoint + "/phim/" + linkSlug
	result := &Result{}
	err := o.GetStruct(endpoint, nil, result)
	if err != nil {
		return nil, err
	}
	if result.Status == false {
		return nil, fmt.Errorf("can not get film info")
	}
	return result, nil
}
