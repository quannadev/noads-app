package film

import (
	"fmt"
	"noads/client"
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
		return false
	}
	return strings.EqualFold(urlParse.Host, "motchill.uk")
}

func (p *MotChillUk) GetLink(link string) (interface{}, error) {
	urlParse, err := p.ParseUrl(link)
	if err != nil {
		return nil, err
	}
	pathOfFilm := strings.Split(urlParse.Path, "/")
	if len(pathOfFilm) < 2 {
		return nil, fmt.Errorf("path of film is invalid")
	}
	pathFilm := pathOfFilm[2]
	body, err := p.Get(link, nil)
	if err != nil {
		return nil, err
	}
	session := p.getSessionInBody(body, pathFilm)
	if session == nil {
		return nil, fmt.Errorf("not found session")
	}
	fmt.Printf("session: %v", session)

	mediaLink, err := p.GetMediaLink(body)
	if err != nil {
		return nil, err
	}
	fmt.Printf("media link: %v", mediaLink)

	data := Model{
		Link:    link,
		Episode: session,
		Media:   mediaLink,
	}

	fmt.Printf("data: %v", data)
	return data, nil
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
