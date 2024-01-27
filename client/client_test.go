package client

import "testing"

func TestGetSlug(t *testing.T) {
	client := NewHttpClient()
	searchQuery := "Cô đi mày bay"
	slug := client.GetSlugTitle(searchQuery)
	if slug != "co-di-may-bay" {
		t.Errorf("Expected %s | %s", "co-di-may-bay", slug)
	}
	t.Logf("Slug: %s", slug)
	searchQuery = "Ương đi ụ bướm"

	slug = client.GetSlugTitle(searchQuery)
	if slug != "uong-di-u-buom" {
		t.Errorf("Expected %s | %s", "uong-di-u-buom", slug)
	}
	t.Logf("Slug: %s", slug)

	searchQuery = "cô+đi+mà+lấy+chồng+tôi"
	slug = client.GetSlugTitle(searchQuery)
	if slug != "co-di-ma-lay-chong-toi" {
		t.Errorf("Expected %s | %s", "co-di-ma-lay-chong-toi", slug)
	}
	t.Logf("Slug: %s", slug)
}
