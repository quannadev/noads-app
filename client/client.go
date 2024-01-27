package client

import (
	"encoding/json"
	"io"
	"net/http"
	"net/url"
	"regexp"
	"strings"
)

type HttpClient struct {
	client *http.Client
}

func NewHttpClient() HttpClient {
	return HttpClient{
		client: &http.Client{},
	}
}

// NewHttpClientWithProxy Init With Proxy
func NewHttpClientWithProxy(proxy string, username, password string) *HttpClient {
	transport := &http.Transport{
		Proxy: http.ProxyURL(&url.URL{
			Host: proxy,
			User: url.UserPassword(username, password),
		}),
	}
	return &HttpClient{
		client: &http.Client{
			Transport: transport,
		},
	}
}

func (c *HttpClient) GetRaw(url string, headers *map[string]string) (*http.Response, error) {
	req, err := http.NewRequest("GET", url, nil)
	if err != nil {
		return nil, err
	}
	if headers != nil {
		for k, v := range *headers {
			req.Header.Add(k, v)
		}
	}
	return c.client.Do(req)
}

// Get Txt body
func (c *HttpClient) Get(url string, headers *map[string]string) (string, error) {
	body, err := c.GetRaw(url, headers)
	if err != nil {
		return "", err
	}
	defer body.Body.Close()

	bodyBytes, err := io.ReadAll(body.Body)
	if err != nil {
		return "", err
	}
	return string(bodyBytes), nil
}

// GetStruct Get Struct body
func (c *HttpClient) GetStruct(url string, headers *map[string]string, v interface{}) error {
	body, err := c.GetRaw(url, headers)
	if err != nil {
		return err
	}
	defer body.Body.Close()

	return json.NewDecoder(body.Body).Decode(v)
}

// PostRaw Post Raw
func (c *HttpClient) PostRaw(url string, headers *map[string]string, body io.Reader) (*http.Response, error) {
	req, err := http.NewRequest("POST", url, body)
	if err != nil {
		return nil, err
	}
	if headers != nil {
		for k, v := range *headers {
			req.Header.Add(k, v)
		}
	}
	return c.client.Do(req)
}

// Post return struct
func (c *HttpClient) Post(url string, headers *map[string]string, body io.Reader, v interface{}) error {
	resp, err := c.PostRaw(url, headers, body)
	if err != nil {
		return err
	}

	defer resp.Body.Close()

	return json.NewDecoder(resp.Body).Decode(v)
}

// ParseUrl Parse url
func (c *HttpClient) ParseUrl(uri string) (*url.URL, error) {
	return url.Parse(uri)
}

func (c *HttpClient) RemoveDuplicates(input []string) []string {
	encountered := map[string]bool{}
	var result []string

	for _, val := range input {
		if encountered[val] == false {
			encountered[val] = true
			result = append(result, val)
		}
	}

	return result
}

func (c *HttpClient) GetSlugTitle(title string) string {
	province := strings.ReplaceAll(title, " ", "-")
	province = strings.ToLower(title)
	var RegexpA = `à|á|ạ|ã|ả|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ`
	var RegexpE = `è|ẻ|ẽ|é|ẹ|ê|ề|ể|ễ|ế|ệ`
	var RegexpI = `ì|ỉ|ĩ|í|ị`
	var RegexpU = `ù|ủ|ũ|ú|ụ|ư|ừ|ử|ữ|ứ|ự`
	var RegexpY = `ỳ|ỷ|ỹ|ý|ỵ`
	var RegexpO = `ò|ỏ|õ|ó|ọ|ô|ồ|ổ|ỗ|ố|ộ|ơ|ờ|ở|ỡ|ớ|ợ`
	var RegexpD = `Đ|đ`
	regA := regexp.MustCompile(RegexpA)
	regE := regexp.MustCompile(RegexpE)
	regI := regexp.MustCompile(RegexpI)
	regO := regexp.MustCompile(RegexpO)
	regU := regexp.MustCompile(RegexpU)
	regY := regexp.MustCompile(RegexpY)
	regD := regexp.MustCompile(RegexpD)
	province = regA.ReplaceAllLiteralString(province, "a")
	province = regE.ReplaceAllLiteralString(province, "e")
	province = regI.ReplaceAllLiteralString(province, "i")
	province = regO.ReplaceAllLiteralString(province, "o")
	province = regU.ReplaceAllLiteralString(province, "u")
	province = regY.ReplaceAllLiteralString(province, "y")
	province = regD.ReplaceAllLiteralString(province, "d")

	// regexp remove charaters in ()
	var RegexpPara = `\(.*\)`
	regPara := regexp.MustCompile(RegexpPara)
	province = regPara.ReplaceAllLiteralString(province, "")
	//replace space to -
	replacements := []string{" ", "_", "+", "http://", "https://"}
	for _, r := range replacements {
		province = strings.ReplaceAll(province, r, "-")
	}
	province = strings.TrimLeft(province, "-")
	province = strings.TrimRight(province, "-")
	return province
}
