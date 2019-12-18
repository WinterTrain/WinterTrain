;; gnetlist development playground
 
(use-modules (ice-9 readline))

(activate-readline)

(define format-line
     (lambda (name value)
         (display name)
         (display value)
         (newline)
     )
)

(define wt:list-elements
  (lambda ()
    (for-each (lambda (name)
      (display "Name: ")
      (display name)
      (let ((element (gnetlist:get-package-attribute name "element"))
      )
      (display " Element: ")
      (display element)
      (display " ")
      (cond 
      ((string=? element "PT")
        (display "Pin 0 ")
        (display (car (car (cdr (gnetlist:get-nets name "0")))))
        (display " Pin 1 ")
        (display (car (car (cdr (gnetlist:get-nets name "1")))))
        (display " Pin 2 ")
        (display (car (car (cdr (gnetlist:get-nets name "2")))))
        (display " ")
      )
      ((string=? element "BG")
      (display "            BG   ")
      ))
      (display (gnetlist:get-package-attribute name "device"))
      (newline)
      )
    ) packages)
  )
)


(define wt
     (lambda (filename)
         (set-current-output-port (open-output-file filename))
	(wt:list-elements)
     )
)



