package film

import (
	"fmt"
	"noads/client"
	"noads/providers/models"
	"regexp"
	"strings"
)

type MotChillUk struct {
	client.HttpClient
}

func NewMotChillUk() *MotChillUk {
	return &MotChillUk{
		client.NewHttpClient(),
	}
}

func (p *MotChillUk) CheckLink(link string) bool {
	urlParse, err := p.ParseUrl(link)
	if err != nil {
		info, err := p.GetFilmInfo(link)
		if err != nil {
			return false
		}
		if info == "" {
			return false
		}
		return true
	}
	return strings.EqualFold(urlParse.Host, "motchill.uk")
}

// GetFilmInfo GetVideoInfo
func (p *MotChillUk) GetFilmInfo(link string) (string, error) {
	slug := p.GetSlugTitle(link)
	if slug == "" {
		return "", fmt.Errorf("not found slug")
	}
	endpoint := fmt.Sprintf("https://motchill.uk/phim/%s", slug)
	body, err := p.Get(endpoint, nil)
	if err != nil {
		return "", err
	}
	pattern := fmt.Sprintf(`https:\/\/motchill\.uk\/xem-phim\/%s\/[^"]+`, slug)
	regex := regexp.MustCompile(pattern)
	linkFilm := regex.FindAllStringSubmatch(body, -1)
	if len(linkFilm) == 0 {
		return "", fmt.Errorf("not found link film")
	}
	links := p.RemoveDuplicates(linkFilm[0])
	if len(links) == 0 {
		return "", fmt.Errorf("not found link film")
	}
	return links[0], nil
}

func (p *MotChillUk) GetLink(link string) (interface{}, error) {
	urlParse, err := p.ParseUrl(link)
	if err != nil {
		info, err := p.GetFilmInfo(link)
		if err != nil {
			return nil, err
		}
		urlParse, _ = p.ParseUrl(info)
	}
	pathOfFilm := strings.Split(urlParse.Path, "/")
	if len(pathOfFilm) < 2 {
		return nil, fmt.Errorf("path of film is invalid")
	}
	slug := pathOfFilm[2]
	body, err := p.Get(urlParse.String(), nil)
	if err != nil {
		fmt.Printf("err: %v\n", err)
		return nil, err
	}
	session := p.getSessionInBody(body, slug)
	if session == nil {
		fmt.Printf("not found session\n")
		return nil, fmt.Errorf("not found session")
	}

	mediaLink, err := p.GetMediaLink(body)
	if err != nil {
		return nil, err
	}
	fmt.Printf("media link: %v", mediaLink)
	titles := p.getTitle(body)
	filmName := ""
	if titles != nil {
		filmName = strings.Join(titles, "|")
	}
	data := models.Model{
		Name:    filmName,
		Link:    link,
		Episode: session,
		Media:   mediaLink,
	}

	return data, nil
}

func (p *MotChillUk) getTitle(body string) []string {
	regex := regexp.MustCompile(`<h1 class="title" itemprop="name">([^<]+)<\/h1>`)
	title := regex.FindAllStringSubmatch(body, -1)
	if len(title) == 0 {
		return nil
	}

	regex = regexp.MustCompile(`<h2 class="title2">([^<]+)<\/h2>`)
	title2 := regex.FindAllStringSubmatch(body, -1)
	if len(title2) == 0 {
		return nil
	}

	title = append(title, title2...)

	titles := make([]string, 0)
	for _, v := range title {
		titles = append(titles, v[1])
	}
	return titles
}

func (p *MotChillUk) getSessionInBody(body, pathUrlDetect string) *map[string]string {
	pattern := fmt.Sprintf(`https:\/\/motchill\.uk\/xem-phim\/%s\/[^"]+`, pathUrlDetect)
	regex := regexp.MustCompile(pattern)
	sessions := regex.FindAllString(body, -1)
	if len(sessions) == 0 {
		return nil
	}

	//remove duplicate in sessions
	links := p.RemoveDuplicates(sessions)
	mapLinks := make(map[string]string, len(links))
	for _, v := range links {
		name := strings.Split(v, "/")
		mapLinks[name[len(name)-1]] = v
	}
	return &mapLinks
}

// GetMediaLink Get Media Link
func (p *MotChillUk) GetMediaLink(body string) ([]string, error) {
	regex := regexp.MustCompile(`data-link="([^"]+)"`)
	link := regex.FindAllStringSubmatch(body, -1)
	if len(link) == 0 {
		return nil, fmt.Errorf("not found link")
	}

	links := make([]string, 0)
	for _, v := range link {
		link := strings.ReplaceAll(v[1], "\\", "")
		links = append(links, link)
	}
	links = p.RemoveDuplicates(links)

	return links, nil
}
